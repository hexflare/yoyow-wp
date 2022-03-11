<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;
use YOYOW\Models\Type\Extension;
use YOYOW\Models\Type\Optional;
use YOYOW\Models\Type\VarDate;
use YOYOW\Models\Type\YYWByteString;
use YOYOW\Models\Type\YYWVarIntType;

abstract class AbstractBaseModel implements BaseModelInterface
{
    public function flipBits(string $bitString): string {
        return "";
    }

    /**
     * @param mixed $integer
     * @return string
     */
    public function write($integer): string {
        return "";
    }

    /**
     * @param Parser $parser
     * @return mixed
     */
    public function read(Parser $parser) {
        return "";
    }

    /**
     * @return int
     */
    public function getByteOrder(): int {
        return 0;
    }

    public function toBytes(): BufferInterface {
        $workerClass_by_classinstance = new \ReflectionObject($this);
        $items = [];
        foreach ($this->getPropertyMap() as $orignKey => $newKey) {
            $property = $workerClass_by_classinstance->getProperty($newKey);
            array_push($items, $property->getValue($this));
        }
        return $this->getTemple()->toBytes($items);
    }

    public function toString() {
        $workerClass_by_classinstance = new \ReflectionObject($this);
        $idx = 0;
        $result = [];
        foreach ($this->getPropertyMap() as $orignKey => $newKey) {
            $type = $this->getTemple()->getItem($idx);
            $property = $workerClass_by_classinstance->getProperty($newKey);
            if ($type instanceof YYWByteString){
                $toStr = $property->getValue($this)->toString();
                $result[$orignKey] = $toStr;
            } else if ($type instanceof YYWVarIntType) {
                $toStr = $property->getValue($this)->toString();
                $result[$orignKey] = $toStr;
            } else if ($type instanceof AbstractBaseModel){
                if($property->getValue($this) != null) {
                    $temValue = $property->getValue($this);
                    $toStr = $property->getValue($this)->toString();
                    if ($temValue instanceof Optional || $temValue instanceof Extension) {
                        if ($temValue->data != null) {
                            $result[$orignKey] = $toStr;
                        }
                    } else {
                        if ($toStr != null) {
                            $result[$orignKey] = $toStr;
                        }
                    }
                }
            } else if ($type instanceof VarDate) {
                $timestamp = $property->getValue($this);
                $date = date("Y-m-d H:i:s", $timestamp);
                $result[$orignKey] = str_replace(' ', 'T', $date);
            }  else {
                $result[$orignKey] = $property->getValue($this);
            }
            $idx++;
        }
        return $result;
    }

    public function pase(array $input) {
        $workerClass_by_classinstance = new \ReflectionObject($this);
        $idx = 0;
        foreach ($this->getPropertyMap() as $orignKey => $newKey) {
            $type = $this->getTemple()->getItem($idx);
            if (array_key_exists($orignKey, $input)) {
                $value = $input[$orignKey];
                $property = $workerClass_by_classinstance->getProperty($newKey);
                $property->setAccessible(true);
                if ($type instanceof AbstractBaseModel) {
                    $valueObj = $type->pase($value);
                    $property->setValue($this, $valueObj);
                } else if ($type instanceof VarDate) {
//                    $value = str_replace("T", " ", $value);
//                    echo " time " . $value . "  *****";
                    $timestamp = strtotime($value);
                    $property->setValue($this, $timestamp);
                } else {
                    $property->setValue($this, $value);
                }
            }
            $idx++;
        }
        return $this;
    }
}