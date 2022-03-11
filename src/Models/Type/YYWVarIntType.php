<?php


namespace YOYOW\Models\Type;


use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Types\AbstractType;
use YOYOW\Models\AbstractBaseModel;
use YOYOW\Models\ModelTemplate;

class YYWVarIntType extends AbstractBaseModel
{
    public $data;
    public $type;

    public function __construct(AbstractType $type, $data = null)
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function toString() {
        return $this->data;
    }

    public function toBytes(): BufferInterface {
        $binary = '';
        $binary .= $this->type->write($this->data);
        return new Buffer($binary);
    }

    public function getPropertyMap(): array {
        return [
            "data" => "data",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem($this->type);
        return $temple;
    }
}