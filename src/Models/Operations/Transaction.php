<?php


namespace YOYOW\Models\Operations;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Buffertools;
use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint16;
use BitWasp\Buffertools\Types\Uint32;
use YOYOW\Models\AbstractBaseModel;
use YOYOW\Models\ModelTemplate;
use YOYOW\Models\Type\VarDate;
use YOYOW\Models\Type\YYWArray;
use YOYOW\YOYOW;
use YOYOW\Crypto\Impl\Serializer\key\WifPrivateKeySerializer;
use YOYOW\Crypto\Impl\Serializer\key\PrivateKeySerializer;

class Transaction extends AbstractBaseModel
{

    public $refBlockNum;
    public $refBlockPrefix;
    public $expiration;
    public $operations;
    public $extensions;
    public $signatures;

    public function __construct()
    {
        $this->extensions = new YYWArray();
    }

    public function pase(array $input) {
        parent::pase($input);
        $this->signatures = $input["signatures"];
        return $this;
    }

    public function toString():array {
        $arr =  parent::toString();
        $arr["signatures"] = $this->signatures;
        return $arr;
    }

    public function getPropertyMap(): array {
        return [
            "ref_block_num" => "refBlockNum",
            "ref_block_prefix" => "refBlockPrefix",
            "expiration" => "expiration",
            "operations" => "operations",
            "extensions" => "extensions",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new Uint16(ByteOrder::LE));
        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new VarDate(new Uint32(ByteOrder::LE)));
        $temple->addItem(new YYWArray(new OperationType()));
        $temple->addItem(new YYWArray());
        return $temple;
    }

    public function generateSignature($chainId = '', $privateKey = "") {
        $buf = $this->toBytes();
        $signData = Buffertools::concat(Buffer::hex($chainId), $buf);
//        echo "buff " . $signData->getHex();

        if (strlen($privateKey)) {
            $wifSerializer = new WifPrivateKeySerializer(new PrivateKeySerializer(YOYOW::getEcAdapter()));
            $private = $wifSerializer->parse($privateKey);
            $sign = $private->signCompact($signData);
            $sign = $sign->getBuffer()->getHex();
//            echo " sign " . $sign;
            $this->signatures[] = $sign;
        }
    }

    public function addOperationType($operationType) {
        if ($this->operations == null) {
            $this->operations = new YYWArray(new OperationType());
            $this->operations->data = [];
        }
        $this->operations->data[] = $operationType;
    }

    public function setHeaderBlockId($blockId) {
        $blockIdBuf = Buffer::hex($blockId);
        $hash = [];
        for ($i = 0; $i < 5; ++$i) {
            $va = (($blockIdBuf->slice($i * 4 + 3, 1)->getInt() & 0xff) << 24) |
                (($blockIdBuf->slice($i * 4 + 2, 1)->getInt() & 0xff) << 16) |
                (($blockIdBuf->slice($i * 4 + 1, 1)->getInt() & 0xff) << 8) |
                ($blockIdBuf->slice($i * 4, 1)->getInt() & 0xff);
            $hash[$i] = intval($va) ;
        }
        $this->refBlockNum = $this->endianReverseU32($hash[0]) & 0xffff;
        $this->refBlockPrefix = $hash[1];
        return $this;
    }

    public function setTransactionTimeStr($timeStr) {
        return $this->setTransactionTime(strtotime($timeStr));
    }

    public function setTransactionTime($time) {
        $headerBlockTimeSec = $time;
//        $nowTime = time();
//        if ($nowTime - $headerBlockTimeSec > 30) {
//            $baseExpirationSec = $headerBlockTimeSec;
//        } else {
//            $baseExpirationSec = $nowTime > $headerBlockTimeSec ? $nowTime : $headerBlockTimeSec;
//        }
//        $baseExpirationSec += 15;
        $this->expiration = $time + 3600;
        return $this;
    }

    public function endianReverseU32(int $integer) {
        return ((($integer >> 0x18) & 0xFF))
            | ((($integer >> 0x10) & 0xFF) << 0x08)
            | ((($integer >> 0x08) & 0xFF) << 0x10)
            | ((($integer) & 0xFF) << 0x18);
    }
}