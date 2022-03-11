<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint64;


class AccountAuthItem extends AbstractBaseModel
{
    public $authType;
    public $uid;

    public function toBytes(): BufferInterface {
        $binary = '';
        $binary .= (new Uint64(ByteOrder::LE))->write($this->uid);
        if ($this->authType == "active_auth") {
            $binary .= (new Uint64(ByteOrder::LE))->write(1);
        } else if ($this->authType == "secondary_auth") {
            $binary .= (new Uint64(ByteOrder::LE))->write(2);
        } else {
            $binary .= (new Uint64(ByteOrder::LE))->write(0);
        }
        return new Buffer($binary);
    }

    public function getPropertyMap(): array {
        return [
            "auth_type" => "authType",
            "uid" => "uid",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint64(ByteOrder::LE));
        return $temple;
    }
}