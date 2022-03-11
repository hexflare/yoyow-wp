<?php


namespace YOYOW\Models\Operations;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint64;
use YOYOW\Models\AccountAuthPlatformExt;
use YOYOW\Models\ModelTemplate;
use YOYOW\Models\Type\Extension;
use YOYOW\Models\Type\Optional;

class AccountAuthPlatformOperation extends BaseOperation
{
    public $uid;
    public $platform;
    public $extensions;


    public function __construct($uid = 0, $platform = 0, $limit = 1000, $permise = 255)
    {
        parent::__construct();
        $this->uid = $uid;
        $this->platform = $platform;
        $ext = new AccountAuthPlatformExt();
        $ext->setLimitForPlatform($limit);
        $ext->setPermissionFlags($permise);
        $this->extensions = new Optional(new Extension($ext));
    }

    public function getPropertyMap(): array {
        $parent = parent::getPropertyMap();
        return array_merge($parent, [
            "uid" => "uid",
            "platform" => "platform",
            "extensions" => "extensions",
        ]);
    }

    public function getTemple(): ModelTemplate {
        $temple = parent::getTemple();
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Optional(new Extension(new AccountAuthPlatformExt())));
        return $temple;
    }
}