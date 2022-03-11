<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint64;

class YYWAsset extends AbstractBaseModel
{
    public $amount;
    public $assetId;

    public function __construct($amount = 0, $assetId = 0)
    {
        $this->amount = $amount;
        $this->assetId = $assetId;
    }

    public function getPropertyMap(): array
    {
        return [
            "amount" => "amount",
            "asset_id" => "assetId",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new Uint64(ByteOrder::LE));
        $temple->addItem(new Uint64(ByteOrder::LE));
        return $temple;
    }
}