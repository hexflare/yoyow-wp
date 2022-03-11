<?php


namespace YOYOW\Models\Type;


use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use YOYOW\Models\AbstractBaseModel;
use YOYOW\Models\ModelTemplate;
use YOYOW\Utils\StringUtils;

class YYWByteString extends AbstractBaseModel
{
    public $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function toString() {
        if ($this->data != null) {
            if ($this->data instanceof BufferInterface) {
                return $this->data->getHex();
            } else {
                return $this->data;
            }
        }
        return $this->data;
    }

    public function toBytes(): BufferInterface {
        $binary = '';
        if ($this->data != null) {
            $buf = null;
            if ($this->data instanceof BufferInterface) {
                $buf = $this->data;
            } else {
                 $buf = Buffer::hex(StringUtils::String2Hex($this->data));
            }
            $binary .= (new Pack())->write($buf->getSize());
            $binary .= $buf->getBinary();
            return new Buffer($binary);
        }
        $binary .= (new Pack())->write(0);
        return new Buffer($binary);
    }

    public function getPropertyMap(): array {
        return [
            "data" => "data",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new Pack());
        return $temple;
    }
}