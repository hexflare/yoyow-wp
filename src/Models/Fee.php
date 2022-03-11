<?php


namespace YOYOW\Models;

use BitWasp\Buffertools\Types\Uint64;
use YOYOW\Models\Type\Extension;
use YOYOW\Models\Type\Optional;

class Fee extends AbstractBaseModel
{
    public $total;
    public $options;

    public function __construct()
    {
        $this->total = new YYWAsset();
    }

    public function getPropertyMap(): array
    {
        return [
            "total" => "total",
            "options" => "options",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new YYWAsset());
        $temple->addItem(new Optional(new Extension(new FeeOptions())));
        return $temple;
    }
}