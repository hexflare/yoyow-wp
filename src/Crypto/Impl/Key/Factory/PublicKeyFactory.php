<?php


namespace YOYOW\Crypto\Impl\Key\Factory;


use YOYOW\YOYOW;
use BitWasp\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use YOYOW\Crypto\Impl\Serializer\Key\PublicKeySerializer;

class PublicKeyFactory
{
    /**
     * @var PublicKeySerializerInterface
     */
    private $serializer;

    /**
     * PublicKeyFactory constructor.
     * @param EcAdapterInterface $ecAdapter
     */
    public function __construct(EcAdapterInterface $ecAdapter = null)
    {
        $ecAdapter = $ecAdapter ? $ecAdapter : YOYOW::getEcAdapter();
        $this->serializer =  new PublicKeySerializer($ecAdapter);
    }

    /**
     * @param string $hex
     * @return PublicKeyInterface
     * @throws \Exception
     */
    public function fromHex(string $hex): PublicKeyInterface
    {
        return $this->fromBuffer(Buffer::hex($hex));
    }

    /**
     * @param BufferInterface $buffer
     * @return PublicKeyInterface
     */
    public function fromBuffer(BufferInterface $buffer): PublicKeyInterface
    {
        return $this->serializer->parse($buffer);
    }
}