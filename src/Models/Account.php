<?php


namespace YOYOW\Models;

use BitWasp\Buffertools\Types\Uint32;
use BitWasp\Buffertools\Types\VarInt;
use BitWasp\Buffertools\Types\VarString;
use YOYOW\Models\Type\VarDate;

class Account extends AbstractBaseModel
{
    public $id;
    public $uid;
    public $name;
    public $owner;
    public $active;
    public $secondary;
    public $memoKey;
    public $createTime;
    public $lastUpdateTime;


    public function getPropertyMap(): array {
        return [
            "id" => "id",
            "uid" => "uid",
            "name" => "name",
            "owner" => "owner",
            "active" => "active",
            "secondary" => "secondary",
            "memo_key" => "memoKey",
            "create_time" => "createTime",
            "last_update_time" => "lastUpdateTime",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new VarString(new VarInt()));
        $temple->addItem(new Uint32());
        $temple->addItem(new VarString(new VarInt()));
        $temple->addItem(new Authority());
        $temple->addItem(new Authority());
        $temple->addItem(new Authority());
        $temple->addItem(new VarString(new VarInt()));
        $temple->addItem(new VarDate(new Uint32()));
        $temple->addItem(new VarDate(new Uint32()));
        return $temple;
    }
}