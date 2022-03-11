<?php


namespace YOYOW\Models;

use BitWasp\Buffertools\Types\Uint16;


class AccountAuth extends AbstractBaseModel
{
    public $key;
    public $value;

    public function toString(): array
    {
        return array($this->key->toString(), $this->value);
    }

    public function pase(array $input) {
        foreach ($input as $key=>$value) {
          if ($key == 0) {
               $this->key =  (new AccountAuthItem())->pase($value);
          } else if ($key == 1) {
              $this->value = $value;
          }
        }
        return $this;
    }

    public function getPropertyMap(): array {
        return [
            "key" => "key",
            "value" => "value",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new AccountAuthItem());
        $temple->addItem(new Uint16());
        return $temple;
    }

}