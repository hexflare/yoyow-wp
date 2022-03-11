<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\ByteOrder;


class Authority extends AbstractBaseModel
{
    public $weightThreshold;
    public $accountUidAuths;
    public $keyAuths;

    public function toBytes(): BufferInterface {

    }

    public function toString(): array
    {
        $accounts = [];
        foreach ($this->accountUidAuths as $accountUidAuth) {
            array_push($accounts, $accountUidAuth->toString());
        }

        $publics = [];
        foreach ($this->keyAuths as $keyAuth) {
            array_push($publics, $keyAuth->toString());
        }
        return ["weight_threshold" => $this->weightThreshold,
            "account_uid_auths" => $accounts,
            "key_auths" => $publics];
    }

    public function pase(array $input) {
        if (array_key_exists("weight_threshold", $input)) {
            $this->weightThreshold = $input["weight_threshold"];
        }
        if (array_key_exists("account_uid_auths", $input)) {
            $accountUidAuthsArr = $input["account_uid_auths"];
            $temp = [];
           if (is_array($accountUidAuthsArr)) {
               foreach ($accountUidAuthsArr as $item) {
                  $itemObj = (new AccountAuth())->pase($item);
                  if ($itemObj != null) {
                      array_push($temp, $itemObj);
                  }
               }
           }
           $this->accountUidAuths = $temp;
        }
        if (array_key_exists("key_auths", $input)) {
            $keyAuthsArr = $input["key_auths"];
            $temp = [];
            if (is_array($keyAuthsArr)) {
                foreach ($keyAuthsArr as $item) {
                    $itemObj = (new AuthPublicKey())->pase($item);
                    if ($itemObj != null) {
                        array_push($temp, $itemObj);
                    }
                }
            }
            $this->keyAuths = $temp;
        }
        return $this;
    }

    public function getPropertyMap(): array {
        return [
            "weight_threshold" => "weightThreshold",
            "account_uid_auths" => "accountUidAuths",
            "key_auths" => "keyAuths",
        ];
    }

    public function getTemple(): ModelTemplate {
        $temple = new ModelTemplate();
        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new AccountAuth());
        $temple->addItem(new AuthPublicKey());
        return $temple;
    }
}