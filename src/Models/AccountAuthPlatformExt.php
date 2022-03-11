<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint32;
use BitWasp\Buffertools\Types\Uint64;
use BitWasp\Buffertools\Types\Uint8;
use YOYOW\Models\Type\Optional;
use YOYOW\Models\Type\YYWVarIntType;

class AccountAuthPlatformExt extends AbstractBaseModel
{
    public $alimitForPlatform;
    public $bpermissionFlags;
    public $cmemo;

    public function __construct()
    {
        $this->alimitForPlatform = new Optional();
        $this->bpermissionFlags = new Optional();
        $this->cmemo = new Optional();

    }

    public function getPropertyMap(): array
    {
        return [
            "limit_for_platform" => "alimitForPlatform",
            "permission_flags" => "bpermissionFlags",
            "memo" => "cmemo",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new Optional(new YYWVarIntType(new Uint8(ByteOrder::LE), 0)));
        $temple->addItem(new Optional(new YYWVarIntType(new Uint32(ByteOrder::LE), 255)));
        $temple->addItem(new Optional(new Memo()));
        return $temple;
    }

    public function setLimitForPlatform($limitForPlatform) {
        $this->alimitForPlatform = new Optional(new YYWVarIntType(new Uint64(ByteOrder::LE), $limitForPlatform));
    }

    public function setPermissionFlags($permissionFlags) {
        $this->bpermissionFlags = new Optional(new YYWVarIntType(new Uint32(ByteOrder::LE), $permissionFlags));
    }
    public function setMemo(Memo $memo) {
        $this->memo = new Optional($memo);
    }
}