<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\ByteOrder;
use YOYOW\Address\Base58Address;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Types\Uint16;
use BitWasp\Buffertools\Types\VarInt;
use BitWasp\Buffertools\Types\VarString;

class AuthPublicKey extends AbstractBaseModel
{
    public $key;
    public $value;

    public function toBytes(): BufferInterface {
        $binary = '';
        $binary .= Base58Address::fromString($this->key)->getBinary();
        $binary .= (new Uint16(ByteOrder::LE))->write($this->value);
        return new Buffer($binary);
    }

    public function toString(): array
    {
        return array($this->key, $this->value);
    }

    public function pase(array $input) {
        foreach ($input as $key=>$value) {
            if ($key == 0) {
                $this->key = $value;
            } else if ($key == 1) {
                $this->value = $value;
            }
        }
        return $this;
    }

    public function getPropertyMap(): array {
        return [
            "key" => "key",
            "value" => "value",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new VarString(new VarInt()));
        $temple->addItem(new Uint16(ByteOrder::LE));
        return $temple;
    }
}