<?php


namespace YOYOW\Utils;


use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class Aes
{
    private $method;
    private $key;
    private $iv;
    private $options;

    public function __construct($key, $iv, $method = "aes-256-cbc", $options = 1)
    {
        $this->key = $key;
        $this->iv = $iv;
        $this->method = $method;
        $this->options = $options;
    }

    public function encrypt($data): BufferInterface {
        $encrypt = openssl_encrypt($data->getBinary(),
            $this->method,
            $this->key->getBinary(),
            $this->options,
            $this->iv->getBinary());
        echo " hex ". StringUtils::String2Hex($encrypt);
        return Buffer::hex(StringUtils::String2Hex($encrypt));
    }

    public function decrypt($data): BufferInterface {
        $decrypt = openssl_decrypt($data->getBinary(),
            $this->method,
            $this->key->getBinary(),
            $this->options,
            $this->iv->getBinary());
        return  Buffer::hex($decrypt);
    }
}