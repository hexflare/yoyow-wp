<?php

namespace YOYOW\Address;

use YOYOW\YOYOW;
use BitWasp\Bitcoin\Crypto\Hash;
use BitWasp\Buffertools\BufferInterface;

abstract class Address implements AddressInterface
{
    /**
     * @var BufferInterface
     */
    protected $hash;

    /**
     * @param BufferInterface $hash
     */
    public function __construct(BufferInterface $hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return BufferInterface
     */
    public function getHash(): BufferInterface
    {
        return $this->hash;
    }

    public static function calculateChecksum(BufferInterface $data): BufferInterface
    {
        return Hash::ripemd160($data)->slice(0, YOYOW::getCheckSumLen());
    }
}