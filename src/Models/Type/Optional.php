<?php


namespace YOYOW\Models\Type;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Types\Uint8;
use YOYOW\Models\AbstractBaseModel;
use YOYOW\Models\ModelTemplate;

class Optional extends AbstractBaseModel
{
    public $data;


    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function toString() {
        if ($this->data != null) {
            return $this->data->toString();
        }
        return null;
    }

    public function pase(array $input) {
        $this->data = $this->data->pase($input);
        return $this;
    }

    public function toBytes(): BufferInterface {
        $binary = '';
        if ($this->data != null) {
            $binary .= (new Uint8())->write(1);
            $binary .= $this->data->toBytes()->getBinary();
        } else {
            $binary .= (new Uint8())->write(0);
        }
        return new Buffer($binary);
    }

    public function getPropertyMap(): array {
        return [
            "data" => "data",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new Uint8());
        return $temple;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }
}
