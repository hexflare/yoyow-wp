<?php

namespace YOYOW\Address;

use BitWasp\Bitcoin\Base58;
use BitWasp\Bitcoin\Exceptions\UnrecognizedAddressException;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Buffertools;
use YOYOW\YOYOW;

class Base58Address extends Address
{

    public function __construct(string $addressStr)
    {
        $this->hash = $this->fromString($addressStr);
    }

    public function getAddress(): string
    {
        $checksum = Address::calculateChecksum($this->hash);
        $payload = Buffertools::concat($this->hash, $checksum);
        return YOYOW::getAddressPrefix() . Base58::encode($payload);
    }

    protected static function readBase58Address(string $strAddress)
    {
        try {
            $encode = substr($strAddress, YOYOW::getAddressPrefixLen(), strlen($strAddress) - YOYOW::getAddressPrefixLen());
            $data = Base58::decode($encode);
            $pubKey = $data->slice(0, $data->getSize() - YOYOW::getCheckSumLen());
            $checksum = $data->slice($data->getSize() - YOYOW::getCheckSumLen(), $data->getSize());
            $calculatedChecksum = Address::calculateChecksum($pubKey);
            if ($checksum->getHex() === $calculatedChecksum->getHex()) {
                return $pubKey;
            }
        } catch (\Exception $e) {
            // Just return null
            return null;
        }

        return null;
    }


    public static function fromString(string $strAddress): BufferInterface
    {
        if (($base58Address = Base58Address::readBase58Address($strAddress)) != null) {
            return $base58Address;
        } else {
            throw new UnrecognizedAddressException();
        }
    }
}