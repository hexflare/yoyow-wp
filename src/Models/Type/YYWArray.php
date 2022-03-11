<?php


namespace YOYOW\Models\Type;


use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use YOYOW\Models\AbstractBaseModel;
use YOYOW\Models\ModelTemplate;

class YYWArray extends AbstractBaseModel
{
    public $data;

    public $type;

    public function __construct($type = null)
    {
        $this->type = $type;
    }

    public function toString()
    {
        if ($this->data) {
            $result = [];
            foreach ($this->data as $item) {
                if ($item instanceof AbstractBaseModel && $item != null) {
                    array_push($result, $item->toString());
                } else if ($item != null) {
                    array_push($result, $item);
                }
            }
            return $result;
        }
        return null;
    }

    public function pase(array $input)
    {
        $result = [];
        foreach ($input as $item) {
            $clone = clone $this->type;
            array_push($clone->pase($item));
        }
        $this->data = $result;
        return $this;
    }

    public function toBytes(): BufferInterface
    {
        $binary = '';
        if ($this->data) {
            $binary .= (new Pack())->write(count($this->data));
            foreach ($this->data as $item) {
                $binary .= $item->toBytes()->getBinary();
            }
        } else {
            $binary .= (new Pack())->write(0);
        }
        return new Buffer($binary);
    }

    public function getPropertyMap(): array
    {
        return [
            "data" => "data",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem($this->type);
        return $temple;
    }
}

