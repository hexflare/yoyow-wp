<?php


namespace YOYOW\Utils;


use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class HashUtils
{
    public static function sha512(BufferInterface $data): BufferInterface {
        $hash = hash('sha512', $data->getBinary(), true);
        return new Buffer($hash, 64);
    }
}