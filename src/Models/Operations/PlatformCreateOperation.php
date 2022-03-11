<?php


namespace YOYOW\Models\Operations;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Int64;
use BitWasp\Buffertools\Types\Uint64;
use YOYOW\Models\ModelTemplate;
use YOYOW\Models\Type\Optional;
use YOYOW\Models\Type\YYWByteString;
use YOYOW\Models\YYWAsset;

class PlatformCreateOperation extends BaseOperation
{
    public $account;
    public $pledge;
    public $name;
    public $url;
    public $extraData;
    public $extensions;

    public function __construct($account = 0, $name = null, $url = "", $extraData = "")
    {
        parent::__construct();
        $this->account = $account;
        $this->pledge = new YYWAsset(10000 * 100000, 0);
        $this->extensions = new Optional();
        $this->name = new YYWByteString($name);
        $this->url = new YYWByteString($url);
        $this->extraData = new YYWByteString($extraData);
    }

    public function getPropertyMap(): array {
        $parent = parent::getPropertyMap();
        return array_merge($parent, [
            "account" => "account",
            "pledge" => "pledge",
            "name" => "name",
            "url" => "url",
            "extra_data" => "extraData",
            "extensions" => "extensions",
        ]);
    }

    public function getTemple(): ModelTemplate {
        $temple = parent::getTemple();
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new YYWAsset());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new Optional(new Int64(ByteOrder::LE)));
        return $temple;
    }
}