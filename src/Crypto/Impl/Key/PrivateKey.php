<?php


namespace YOYOW\Crypto\Impl\Key;


use BitWasp\Bitcoin\Crypto\Hash;
use BitWasp\Buffertools\Buffertools;
use YOYOW\YOYOW;
use YOYOW\Crypto\Impl\Adapter\EcAdapter;
use YOYOW\Crypto\Impl\Key\PublicKey;
use YOYOW\Crypto\Impl\Serializer\Key\PrivateKeySerializer;
use YOYOW\Crypto\Impl\Signature\CompactSignature;
use YOYOW\Crypto\Impl\Signature\Signature;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\Key;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\KeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PrivateKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Signature\CompactSignatureInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Signature\SignatureInterface;
use BitWasp\Bitcoin\Crypto\Random\RbgInterface;
use BitWasp\Bitcoin\Crypto\Random\Rfc6979;
use BitWasp\Bitcoin\Exceptions\InvalidPrivateKey;
use YOYOW\Crypto\Impl\Serializer\Key\WifPrivateKeySerializer;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use YOYOW\Crypto\Impl\Key\Signer;
use BitWasp\Bitcoin\Network\NetworkInterface;

class PrivateKey extends Key implements PrivateKeyInterface
{
    /**
     * @var \GMP
     */
    private $secretMultiplier;

    /**
     * @var bool
     */
    private $compressed;

    /**
     * @var PublicKey
     */
    private $publicKey;

    /**
     * @var EcAdapter
     */
    private $ecAdapter;

    /**
     * @param EcAdapter $ecAdapter
     * @param \GMP $int
     * @param bool $compressed
     * @throws InvalidPrivateKey
     */
    public function __construct(EcAdapter $ecAdapter, \GMP $int, bool $compressed = false)
    {
        if (false === $ecAdapter->validatePrivateKey(Buffer::int(gmp_strval($int, 10), 32))) {
            throw new InvalidPrivateKey('Invalid private key - must be less than curve order.');
        }

        $this->ecAdapter = $ecAdapter;
        $this->secretMultiplier = $int;
        $this->compressed = $compressed;
    }

    /**
     * @return \GMP
     */
    public function getSecret(): \GMP
    {
        return $this->secretMultiplier;
    }

    /**
     * @param BufferInterface $msg32
     * @param RbgInterface|null $rbg
     * @return Signature
     */
    public function sign(BufferInterface $msg32, RbgInterface $rbg = null): SignatureInterface
    {
        $rbg = $rbg ?: new Rfc6979($this->ecAdapter, $this, $msg32);
        $randomK = gmp_init($rbg->bytes(32)->getHex(), 16);

        $hash = gmp_init($msg32->getHex(), 16);

        $math = $this->ecAdapter->getMath();
        $signer = new Signer($math);
        $signature = $signer->sign($this->ecAdapter->getGenerator()->getPrivateKeyFrom($this->secretMultiplier), $hash, $randomK);
        $s = $signature->getS();
        // if s is less than half the curve order, invert s
//        if (!$this->ecAdapter->validateSignatureElement($s, true)) {
//            $s = $math->sub($this->ecAdapter->getOrder(), $s);
//        }

        return new Signature($this->ecAdapter, $signature->getR(), $s);
    }

    /**
     * @param BufferInterface $msg32
     * @param RbgInterface|null $rbg
     * @return CompactSignatureInterface
     * @throws \Exception
     */
    public function signCompact(BufferInterface $msg32, RbgInterface $rbg = null): CompactSignatureInterface
    {
        $msg32 = Hash::sha256($msg32);
        $signature = null;
        $nonce = 0;
        do {
            if ($nonce > 0) {
                $temp = '';
                for ($i = 0; $i < $nonce; $i++) {
                    $temp = $temp . "\x00";
                }
                $rMsg = Buffertools::concat($msg32, new Buffer($temp));
                $rMsg = Hash::sha256($rMsg);
                $rbg =  new Rfc6979($this->ecAdapter, $this, $rMsg);
            }
            $sign = $this->sign($msg32, $rbg);
//            echo "r ". gmp_strval($sign->getR(), 16) . "  s " . gmp_strval($sign->getS(), 16) . " ******  ";

            // calculate the recovery param
            // there should be a way to get this when signing too, but idk how ...
            $signature = new CompactSignature(
                $this->ecAdapter,
                $sign->getR(),
                $sign->getS(),
                $this->ecAdapter->calcPubKeyRecoveryParam($sign->getR(), $sign->getS(), $msg32, $this->getPublicKey()),
                $this->isCompressed()
            );
            $nonce++;
        } while (!$signature->isCanonical());
        return $signature;
    }

    /**
     * @param \GMP $tweak
     * @return KeyInterface
     */
    public function tweakAdd(\GMP $tweak): KeyInterface
    {
        $adapter = $this->ecAdapter;
        $modMath = $adapter->getMath()->getModularArithmetic($adapter->getGenerator()->getOrder());
        return $adapter->getPrivateKey($modMath->add($tweak, $this->getSecret()), $this->compressed);
    }

    /**
     * @param \GMP $tweak
     * @return KeyInterface
     */
    public function tweakMul(\GMP $tweak): KeyInterface
    {
        $adapter = $this->ecAdapter;
        $modMath = $adapter->getMath()->getModularArithmetic($adapter->getGenerator()->getOrder());
        return $adapter->getPrivateKey($modMath->mul($tweak, $this->getSecret()), $this->compressed);
    }

    /**
     * {@inheritDoc}
     */
    public function isCompressed(): bool
    {
        return $this->compressed;
    }

    /**
     * Return the public key
     *
     * @return PublicKey
     */
    public function getPublicKey(): PublicKeyInterface
    {
        if (null === $this->publicKey) {
            $point = $this->ecAdapter->getGenerator()->mul($this->secretMultiplier);
            $this->publicKey = new PublicKey($this->ecAdapter, $point, $this->compressed);
        }

        return $this->publicKey;
    }

    /**
     * @param NetworkInterface $network
     * @return string
     */
    public function toWif(NetworkInterface $network = null): string
    {
        $serializer = new WifPrivateKeySerializer(
            new PrivateKeySerializer($this->ecAdapter)
        );
        return $serializer->serialize($this);
    }

    /**
     * @return BufferInterface
     */
    public function getBuffer(): BufferInterface
    {
        return (new PrivateKeySerializer($this->ecAdapter))->serialize($this);
    }
}