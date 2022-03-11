<?php


namespace YOYOW\Models;


use YOYOW\Models\Type\Optional;

class FeeOptions extends AbstractBaseModel
{
    public $fromBalance;
    public $fromPrepaid;
    public $fromZCsaf;

    public function getPropertyMap(): array
    {
        return [
            "from_balance" => "fromBalance",
            "from_prepaid" => "fromPrepaid",
            "from_csaf" => "fromZCsaf",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new Optional(new YYWAsset()));
        $temple->addItem(new Optional(new YYWAsset()));
        $temple->addItem(new Optional(new YYWAsset()));
        return $temple;
    }
}