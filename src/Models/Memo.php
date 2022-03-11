<?php


namespace YOYOW\Models;


use BitWasp\Bitcoin\Crypto\Hash;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Buffertools;
use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Int64;
use BitWasp\Buffertools\Types\Uint64;
use BitWasp\Buffertools\Types\VarInt;
use BitWasp\Buffertools\Types\VarString;
use YOYOW\Address\Base58Address;
use YOYOW\Crypto\Impl\Key\Factory\PublicKeyFactory;
use YOYOW\Crypto\Impl\Key\PrivateKey;
use YOYOW\Models\Type\Pack;
use YOYOW\Models\Type\YYWByteString;
use YOYOW\Utils\Aes;
use YOYOW\Utils\HashUtils;
use YOYOW\Utils\StringUtils;
use YOYOW\YOYOW;

class Memo extends AbstractBaseModel
{
    public $from;
    public $to;
    public $nonce;
    public $message;

    public function __construct($fromPublic = null, $toPublicKey = null, $nonce = 0)
    {
        if ($fromPublic && $toPublicKey) {
            if ($nonce <= 0) {
                $nonce = $this->generateNonce();
            }
            $this->nonce = $nonce;
            $this->from = $fromPublic;
            $this->to = $toPublicKey;
        }
    }

    public function toBytes(): BufferInterface {
        $binary = '';
        $binary .= Base58Address::fromString($this->from)->getBinary();
        $binary .= Base58Address::fromString($this->to)->getBinary();
        $binary .= (new Uint64(ByteOrder::LE))->write($this->nonce);
        $binary .= (new Pack())->write($this->message->getSize());
        $binary .= $this->message->getBinary();
        return new Buffer($binary);
    }

    public function toString() {
        return [
            "from" => $this->from,
            "to" => $this->to,
            "nonce" => $this->nonce,
            "message" => $this->message->getHex(),
        ];
    }

    public function pase(array $input) {
        $this->from = $input["from"];
        $this->to = $input["to"];
        $this->nonce = $input["nonce"];
        $this->message = Buffer::hex($input["message"]);
        return $this;
    }

    public function getPropertyMap(): array
    {
        return [
            "from" => "from",
            "to" => "to",
            "nonce" => "nonce",
            "message" => "message",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new VarString(new VarInt()));
        $temple->addItem(new VarString(new VarInt()));
        $temple->addItem(new Int64(ByteOrder::LE));
        $temple->addItem(new YYWByteString());
        return $temple;
    }

    public function generateNonce() {
        $nonce = mt_rand(99999, 100000000000);
        $nonce += mt_rand(99999, 100000000000);
        $nonce = $nonce << 2;
        $nonce = $nonce & 0xffffffff;
        return $nonce;
    }

    public function setMemoMessage(PrivateKey $privateKey,  string $messagePlain) {
        if ($this->to == null) {
            throw new \Exception('Memo no to public key!');
        }
        if ($this->nonce <= 0) {
            $this->nonce = $this->generateNonce();
        }
        $this->message = $this->encryptMessage($privateKey, $this->to, $messagePlain, $this->nonce);
    }

    public function encryptMessage(PrivateKey $privateKey, string $toPublicKey, string $messagePlain, int $nonce): BufferInterface
    {
        $address = new Base58Address($toPublicKey);
        $pubkey = (new PublicKeyFactory(YOYOW::getEcAdapter()))->fromBuffer($address->getHash());
        $share = $this->getShareSecret($privateKey, $pubkey);

        echo " Secret " . gmp_strval($share, 16);

        $sha512 = HashUtils::sha512(Buffer::gmp($share));
        $nonceStr = $nonce . $sha512->getHex();
        $nonceBuffer =  Buffer::hex(StringUtils::String2Hex($nonceStr));
        $sha512 = HashUtils::sha512($nonceBuffer);
        echo " sha512 " . $sha512->getHex();

        $messageBuffer = Buffer::hex(StringUtils::String2Hex($messagePlain));
        $sha256 = Hash::sha256($messageBuffer);

        $checkSumBuffer = $sha256->slice(0, 4);
        $msgFinal = Buffertools::concat($checkSumBuffer, $messageBuffer);
        echo " msgFinal " . $msgFinal->getHex();

        $aes = new Aes($sha512->slice(0, 32), $sha512->slice(32, 16));
        $encrypt = $aes->encrypt($msgFinal);
        return $encrypt;
    }

    public function decryptMessage(PrivateKey $privateKey, string $toPublicKey, BufferInterface $message, int $nonce): BufferInterface
    {
        $address = new Base58Address($toPublicKey);
        $pubkey = (new PublicKeyFactory(YOYOW::getEcAdapter()))->fromBuffer($address->getHash());
        $share = $this->getShareSecret($privateKey, $pubkey);

        $sha512 = HashUtils::sha512(Buffer::gmp($share));
        $nonceStr = $nonce . $sha512->getHex();
        $nonceBuffer =  Buffer::hex(StringUtils::String2Hex($nonceStr));

        $sha512 = HashUtils::sha512($nonceBuffer);

        $aes = new Aes($sha512->slice(0, 32), $sha512->slice(32, 16));
        $decrypt = $aes->decrypt($message);
        if ($decrypt) {
            throw new \Exception('Invalid decrypt message!');
            return null;
        }
        $checkSum = $decrypt->slice(0, 4);
        $decryptMessage = $decrypt->slice(4);
        $confirmCheckSum = Hash::sha256($decryptMessage)->slice(0, 4);
        if ($checkSum->getHex() == $confirmCheckSum->getHex()) {
            return StringUtils::Hex2String($decryptMessage->getHex());
        }
        throw new \Exception('Invalid decrypt message!');
    }

    public function getShareSecret($privateKey, $publicKey): \GMP {
       return $publicKey->getPoint()->mul($privateKey->getSecret())->getX();
    }
}