<?php


namespace YOYOW\Models\Operations;

use YOYOW\Constant\OperationTypeId;
use YOYOW\Models\AbstractBaseModel;
use YOYOW\Models\ModelTemplate;
use YOYOW\Models\Type\Pack;
use YOYOW\Models\Type\VarDate;

class OperationType extends AbstractBaseModel
{
    public $operationType;
    public $content;

    public function __construct($operationType = 0, $content = null)
    {
        $this->operationType = $operationType;
        $this->content = $content;
    }

    public function toString(): array {
        return [$this->operationType, $this->content->toString()];
    }

    public function pase(array $input) {
        foreach ($input as $key=>$value) {
            if ($key == 0) {
                $this->operationType = $value;
            } else if ($key == 1) {
                 $typeClassName = OperationTypeId::getOperationClassNameFromId($this->operationType);
                $workerClass_by_class = new \ReflectionClass($typeClassName);
                $type = $workerClass_by_class->newInstance();
                 $this->content = $type->pase($value);
            }
        }
        return $this;
    }


    public function getPropertyMap(): array {
        return [
            "operationType" => "operationType",
            "content" => "content",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new Pack());
        $temple->addItem(new BaseOperation());
        return $temple;
    }
}