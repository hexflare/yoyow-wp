<?php


namespace YOYOW\Models\Operations;


use YOYOW\Models\AbstractBaseModel;
use YOYOW\Models\Fee;
use YOYOW\Models\FeeOptions;
use YOYOW\Models\ModelTemplate;
use YOYOW\Models\Type\Extension;
use YOYOW\Models\Type\Optional;
use YOYOW\Models\YYWAsset;

class BaseOperation extends AbstractBaseModel
{
    public $fee;

    public function __construct()
    {
        $this->fee = new Fee();
    }

    public function getPropertyMap(): array {
        return [
            "fee" => "fee",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new Fee());
        return $temple;
    }

    public function isUseBalance() {
        return true;
    }

    public function isUseCasf() {
        return false;
    }

    public function updateFee($minFee, $minRealFee) {
        $asset = $this->fee->total;
        $this->fee->total = new YYWAsset($minFee, $asset->assetId);
        $feeOptions = new FeeOptions();
        $csaf = $minFee - $minRealFee;

        //minRealFee: 必须以资产作为手续费,不能以积分抵扣
        if ($minRealFee > 0) {
            if ($this->isUseBalance()) {
                $feeOptions->fromBalance = new Optional(new YYWAsset($minRealFee, $asset->assetId));
            } else {
                $feeOptions->fromPrepaid = new Optional(new YYWAsset($minRealFee, $asset->assetId));
            }
        } else {
            //minFee: 可用积分抵扣
            if ($this->isUseCasf()) {
                //使用积分抵扣手续费
                $feeOptions->fromZCsaf = new Optional(new YYWAsset($csaf, $asset->assetId));
            } else {
                if ($this->isUseBalance()) {
                    $feeOptions->fromBalance = new Optional(new YYWAsset($minFee, $asset->assetId));
                } else {
                    $feeOptions->fromPrepaid = new Optional(new YYWAsset($minFee, $asset->assetId));
                }
            }
        }
        $this->fee->options = new Optional(new Extension($feeOptions));
    }
}