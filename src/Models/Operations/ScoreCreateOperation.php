<?php


namespace YOYOW\Models\Operations;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint64;
use BitWasp\Buffertools\Types\Uint8;
use YOYOW\Models\Memo;
use YOYOW\Models\ModelTemplate;
use YOYOW\Models\TransferOption;
use YOYOW\Models\Type\Extension;
use YOYOW\Models\Type\Optional;
use YOYOW\Models\Type\YYWVarIntType;
use YOYOW\Models\YYWAsset;

class ScoreCreateOperation extends BaseOperation
{
    public $fromAccount;
    public $platform;
    public $poster;
    public $postPid;
    public $score;
    public $csaf;
    public $signPlatform;
    public $extensions;

    public function __construct($from = 0, $platform = 0, $poster = 0, $postPid = 0)
    {
        parent::__construct();
        $this->fromAccount = $from;
        $this->platform = $platform;
        $this->poster = $poster;
        $this->extensions = new Optional();
        $this->score = 0;
        $this->postPid = $postPid;
        $this->csaf = 5;
        $signPlatform = new YYWVarIntType(new Uint64(ByteOrder::LE));
        $signPlatform->data = $platform;
        $this->signPlatform = new Optional($signPlatform);
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
            "from_account_uid" => "fromAccount",
            "platform" => "platform",
            "poster" => "poster",
            "post_pid" => "postPid",
            "score" => "score",
            "csaf" => "csaf",
            "sign_platform" => "signPlatform",
            "extensions" => "extensions",
        ]);
    }

    public function getTemple(): ModelTemplate {
        $temple = parent::getTemple();
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint8(ByteOrder::LE));
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Optional());
        $temple->addItem(new Optional());
        return $temple;
    }
}