<?php


namespace YOYOW\Models\Operations;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint64;
use YOYOW\Models\Memo;
use YOYOW\Models\ModelTemplate;
use YOYOW\Models\TransferOption;
use YOYOW\Models\Type\Extension;
use YOYOW\Models\Type\Optional;
use YOYOW\Models\YYWAsset;

class TransferOperation extends BaseOperation
{
    public $from;
    public $to;
    public $amount;
    public $memo;
    public $extensions;

    public function __construct($from = 0, $to = 0, $amount = 0)
    {
        parent::__construct();
        $this->from = $from;
        $this->to = $to;
        $this->amount = new YYWAsset($amount);
        $this->extensions = new Optional();
        $this->memo = new Optional();
    }

    public function getPropertyMap(): array {
        $parent = parent::getPropertyMap();
        return array_merge($parent, [
            "from" => "from",
            "to" => "to",
            "amount" => "amount",
            "memo" => "memo",
            "extensions" => "extensions",
        ]);
    }

    public function setMemo(Memo $memo) {
        $this->memo = new Optional($memo);
    }

    public function getTemple(): ModelTemplate {
        $temple = parent::getTemple();
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new YYWAsset());
        $temple->addItem(new Optional(new Memo()));
        $temple->addItem(new Optional(new Extension(new TransferOption())));
        return $temple;
    }
}