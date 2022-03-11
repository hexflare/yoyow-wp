<?php


namespace YOYOW\Models\Operations;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint64;
use YOYOW\Models\ModelTemplate;
use YOYOW\Models\PostExtension;
use YOYOW\Models\Type\Extension;
use YOYOW\Models\Type\Optional;
use YOYOW\Models\Type\YYWByteString;

class PostOperation extends BaseOperation
{
    public $postPid;
    public $platform;
    public $poster;
    public $originPoster;
    public $originPostPid;
    public $originPlatform;

    public $hashValue;
    public $extraData;
    public $title;
    public $body;
    public $extensions;

    public function __construct($postPid = 0, $platform = 0, $poster = 0,  $title = null, $body = null, $hashValue = null, $extraData = null)
    {
        parent::__construct();
        $this->platform = $platform;
        $this->postPid = $postPid;
        $this->poster = $poster;

        $this->hashValue = new YYWByteString($hashValue);
        $this->title = new YYWByteString($title);
        $this->body = new YYWByteString($body);
        $this->extraData = new YYWByteString($extraData);
        $this->originPostPid = new Optional();
        $this->originPoster = new Optional();
        $this->originPlatform = new Optional();

        $postExtension = new PostExtension();
        $postExtension->setSignPlatform($platform);
        $this->extensions = new Optional(new Extension($postExtension));
    }

    public function isUseBalance() {
        return false;
    }

    public function isUseCasf() {
        return true;
    }

    public function getPropertyMap(): array {
        $parent = parent::getPropertyMap();
        return array_merge($parent, [
            "post_pid" => "postPid",
            "platform" => "platform",
            "poster" => "poster",
            "origin_poster" => "originPoster",
            "origin_post_pid" => "originPostPid",
            "origin_platform" => "originPlatform",
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
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Optional());
        $temple->addItem(new Optional());
        $temple->addItem(new Optional());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new YYWByteString());
        $temple->addItem(new Optional());
        return $temple;
    }


}