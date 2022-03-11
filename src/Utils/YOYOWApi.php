<?php


namespace YOYOW\Utils;


use YOYOW\Models\Account;
use YOYOW\Models\FullAccount;
use YOYOW\Models\RequireFee;
use YOYOW\Models\YYWObject;

class YOYOWApi
{
    public static function getBlockObject(): YYWObject {
        $data = HttpClient::getInstance()->setRpcType(HttpClient::RPC_TYPE_DATABASE)->get_objects(["2.1.0"]);
        return (new YYWObject())->pase($data[0]);
    }

    public static function getObject(string $object_id) {
        return HttpClient::getInstance()->setRpcType(HttpClient::RPC_TYPE_DATABASE)->get_objects([$object_id]);
    }

    public static function lookupAssetSymbols(array $idOrSymbols) {
        return HttpClient::getInstance()->setRpcType(HttpClient::RPC_TYPE_DATABASE)->lookup_asset_symbols($idOrSymbols);
    }

    public static function getAssetSymbols(array $symbols) {
        return HttpClient::getInstance()->setRpcType(HttpClient::RPC_TYPE_DATABASE)->get_assets($symbols);
    }

    public static function getAccountsById($accountID): Account {
        $data = HttpClient::getInstance()->setRpcType(HttpClient::RPC_TYPE_DATABASE)->get_accounts_by_uid([$accountID]);
        return (new Account())->pase($data[0]);
    }

    public static function getAccountsByIds(array $accountIDs): array {
        $data = HttpClient::getInstance()->setRpcType(HttpClient::RPC_TYPE_DATABASE)->get_accounts_by_uid($accountIDs);
        $result = [];
        foreach ($data as $item) {
            $res = (new Account())->pase($item);
            if ($res != null) {
                array_push($result, $res);
            }
        }
        return $result;
    }

    public static function getFullAccountById($accountId): FullAccount {
        $res = YOYOWApi::getFullAccountsByIds([$accountId]);
        if (count($res) > 0) {
            return $res[0];
        }
        return null;
    }
    public static function getFullAccountsByIds(array $accountIDs) : array {
        $fetchs = array(
            "fetch_statistics"     => 1,
            "fetch_account_object" => 1,
            "fetch_platform_object" => 1,
            "fetch_voter_object"   => 1,
            "fetch_committee_member_votes" => 1,
            "fetch_witness_votes" => 1,
            "fetch_committee_member_object" => 1,
            "fetch_witness_object" => 1,
            "fetch_platform_votes" => 1,
        );
        $data = HttpClient::getInstance()->setRpcType(HttpClient::RPC_TYPE_DATABASE)->get_full_accounts_by_uid($accountIDs, $fetchs);
        $result = [];
        foreach ($data as $item) {
            $res = (new FullAccount())->pase($item[1]);
            if ($res != null) {
                array_push($result, $res);
            }
        }
        return $result;
    }

    public static function getRequireFeeWithOperations(array $operationTypes): array {
        $params = [];
        foreach ($operationTypes as $item) {
            array_push($params, $item->toString());
        }
        $data = HttpClient::getInstance()->setRpcType(HttpClient::RPC_TYPE_DATABASE)->get_required_fee_data($params);
        $result = [];
        foreach ($data as $item) {
            $res = (new RequireFee())->pase($item);
            if ($res != null) {
                array_push($result, $res);
            }
        }
        return $result;
    }

    public static function broadCastTransaction($transaction) {
        $params = $transaction->toString();
        $data = HttpClient::getInstance()->setRpcType(HttpClient::RPC_TYPE_BROADCAST)->
        broadcast_transaction_with_callback(HttpClient::$id, $params);
        return $data;
    }
}