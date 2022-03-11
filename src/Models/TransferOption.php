<?php


namespace YOYOW\Models;


use YOYOW\Models\Type\Optional;

class TransferOption extends AbstractBaseModel
{
    public $fromBalance;
    public $fromPrepaid;
    public $toBalance;
    public $toPrepaid;

    public function getPropertyMap(): array
    {
        return [
            "from_balance" => "fromBalance",
            "from_prepaid" => "fromPrepaid",
            "to_balance" => "toBalance",
            "to_prepaid" => "toPrepaid",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new Optional(new YYWAsset()));
        $temple->addItem(new Optional(new YYWAsset()));
        $temple->addItem(new Optional(new YYWAsset()));
        $temple->addItem(new Optional(new YYWAsset()));
        return $temple;
    }
}