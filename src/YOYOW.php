<?php

namespace YOYOW;

use BitWasp\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Bitcoin\Math\Math;
use Mdanter\Ecc\EccFactory;
use YOYOW\Crypto\Impl\Adapter\EcAdapter;

class YOYOW
{
    private static $chainId = null;
    private static $endpoint = null;

    public static function getChainId() {
        return YOYOW::$chainId;
    }

    public static function getEndPoint() {
        return YOYOW::$endpoint;
    }

    public static function setChainId(string $chainId) {
        YOYOW::$chainId = $chainId;
    }

    public static function setEndPoint(string $endpoint) {
        YOYOW::$endpoint = $endpoint;
    }

    public static function getAddressPrefix(): string {
        return "YYW";
    }

    public static function getAddressPrefixLen(): int {
        return 3;
    }

    public static function getCheckSumLen(): int {
        return 4;
    }

    private static $adapter;

    public static function getMath()
    {
        return new Math();
    }

    public static function getGenerator()
    {
        return EccFactory::getSecgCurves(self::getMath())->generator256k1();
    }

    public static function getEcAdapter(): EcAdapterInterface
    {
        if (null === self::$adapter) {
            self::$adapter = new EcAdapter(self::getMath(), self::getGenerator());
        }
        return self::$adapter;
    }

    public static function setAdapter(EcAdapterInterface $adapter)
    {
        self::$adapter = $adapter;
    }
}