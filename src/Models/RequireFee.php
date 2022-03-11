<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint32;

class RequireFee  extends AbstractBaseModel
{
    public $feePayerUid;
    public $minFee;
    public $minRealFee;

    public function getPropertyMap(): array
    {
        return [
            "fee_payer_uid" => "feePayerUid",
            "min_fee" => "minFee",
            "min_real_fee" => "minRealFee",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new Uint32(ByteOrder::LE));
        return $temple;
    }
}