<?php


namespace YOYOW\Models\Type;


use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Types\Uint8;
use YOYOW\Models\AbstractBaseModel;
use YOYOW\Models\ModelTemplate;

class Extension extends AbstractBaseModel
{
    public $data;

    public function __construct($data)
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
        $workerClass_by_classinstance = new \ReflectionObject($this->data);
        $propertyNames = [];
        $countNotNull = 0;
        foreach ($workerClass_by_classinstance->getProperties() as $property) {
            $propertyNames[] = $property->name;
            $propertyValue = $property->getValue($this->data);
            if ($propertyValue instanceof Optional) {
                if ($propertyValue->getData() != null) {
                    $countNotNull++;
                }
            } else {
                if ($propertyValue != null) {
                    $countNotNull++;
                };
            }
        }
        sort($propertyNames);
        $binary .= (new Pack())->write($countNotNull);
        $idx = 0;
//        echo " $propertyNames " . print_r($propertyNames);
        foreach ($propertyNames as $propertyName) {
            $property = $workerClass_by_classinstance->getProperty($propertyName);
            $propertyValue = $property->getValue($this->data);
            if ($propertyValue) {
                if ($propertyValue instanceof Optional) {
                    if ($propertyValue->getData() != null) {
                        $binary .= (new Pack())->write($idx);
                        $binary .= $propertyValue->getData()->toBytes()->getBinary();
                    }
                } else {
                    if ($propertyValue != null) {
                        $binary .= $propertyValue->toBytes()->getBinary();
                    }
                }
            }
            $idx++;
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

}