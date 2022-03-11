<?php


namespace YOYOW\Models\Operations;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint64;
use BitWasp\Buffertools\Types\Uint8;
use YOYOW\Models\ModelTemplate;
use YOYOW\Models\Type\Optional;
use YOYOW\Models\Type\YYWByteString;

class LicenseCreateOperation extends BaseOperation
{
    public $licenseLid;
    public $platform;
    public $type;
    public $hashValue;
    public $extraData;
    public $title;
    public $body;
    public $extensions;

    public function __construct($platform = 0, $licenseLid = 0, $type = 0, $title = null, $body = null, $hashValue = null, $extraData = null)
    {
        parent::__construct();
        $this->platform = $platform;
        $this->licenseLid = $licenseLid;
        $this->type = $type;
        $this->extensions = new Optional();
        $this->hashValue = new YYWByteString($hashValue);
        $this->title = new YYWByteString($title);
        $this->body = new YYWByteString($body);
        $this->extraData = new YYWByteString($extraData);
    }

    public function getPropertyMap(): array {
        $parent = parent::getPropertyMap();
        return array_merge($parent, [
            "license_lid" => "licenseLid",
            "platform" => "platform",
            "type" => "type",
            "hash_value" => "hashValue",
            "extra_data" => "extraData",
            "title" => "title",
            "body" => "body",
            "extensions" => "extensions",
        ]);
    }

    public function getTemple(): ModelTemplate {
        $temple = parent::getTemple();
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint8(ByteOrder::LE));
        $temple->addItem(new YYWByteString());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new Optional(new Uint8()));
        return $temple;
    }
}