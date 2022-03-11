<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint64;
use BitWasp\Buffertools\Types\Uint8;
use BitWasp\Buffertools\Types\Uint32;
use YOYOW\Models\Type\Optional;
use YOYOW\Models\Type\YYWByteString;
use YOYOW\Models\Type\YYWVarIntType;

class PostExtension extends AbstractBaseModel
{
    public $apostType;
    public $bforwardPrice;
    public $clicenseLid;
    public $dpermissionFlags;
    public $ereceiptors;
    public $fsignPlatform;

    public function __construct()
    {
        $this->apostType = new Optional(new YYWVarIntType(new Uint8(ByteOrder::LE), 0));
        $this->bforwardPrice = new Optional();
        $this->clicenseLid = new Optional(new YYWVarIntType(new Uint64(ByteOrder::LE), 1));
        $this->dpermissionFlags = new Optional(new YYWVarIntType(new Uint32(ByteOrder::LE), 255));
        $this->ereceiptors = new Optional();
        $this->fsignPlatform = new Optional();

    }

    public function getPropertyMap(): array
    {
        return [
            "post_type" => "apostType",
            "forward_price" => "bforwardPrice",
            "license_lid" => "clicenseLid",
            "permission_flags" => "dpermissionFlags",
            "receiptors" => "ereceiptors",
            "sign_platform" => "fsignPlatform",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new Optional(new YYWVarIntType(new Uint8(ByteOrder::LE), 0)));
        $temple->addItem(new Optional());
        $temple->addItem(new Optional(new YYWVarIntType(new Uint64(ByteOrder::LE), 1)));
        $temple->addItem(new Optional(new YYWVarIntType(new Uint32(ByteOrder::LE), 255)));
        $temple->addItem(new Optional());
        $temple->addItem(new Optional());
        return $temple;
    }

    public function setSignPlatform($signPlatform) {
        $this->fsignPlatform = new Optional(new YYWVarIntType(new Uint64(ByteOrder::LE), $signPlatform));
    }
}