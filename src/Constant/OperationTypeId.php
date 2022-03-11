<?php


namespace YOYOW\Constant;

use YOYOW\Models\Operations\AccountAuthPlatformOperation;
use YOYOW\Models\Operations\LicenseCreateOperation;
use YOYOW\Models\Operations\PlatformCreateOperation;
use YOYOW\Models\Operations\PostOperation;
use YOYOW\Models\Operations\ScoreCreateOperation;
use \YOYOW\Models\Operations\TransferOperation;

class OperationTypeId
{
    const ID_TRANSER_OPERATION = 0;
    const ID_ACCOUNT_CREATE_OPERATION = 1;
    const ID_ACCOUNT_MANAGE_OPERATION = 2;
    const ID_ACCOUNT_UPDATE_AUTH_OPERATION = 3;
    const ID_ACCOUNT_UPDATE_KEY_OPERATION = 4;
    const ID_ACCOUNT_UPDATE_PROXY_OPERATION = 5;
    const ID_CSAF_COLLECT_OPERATION = 6;
    const ID_CSAF_LEASE_OPERATION = 7;
    const ID_COMMITTEE_MEMBER_CREATE_OPERATION = 8;
    const ID_COMMITTEE_MEMBER_UPDATE_OPERATION = 9;
    const ID_COMMITTEE_MEMBER_VOTE_UPDATE_OPERATION = 10;
    const ID_COMMITTEE_PROPOSAL_CREATE_OPERATION = 11;
    const ID_COMMITTEE_PROPOSAL_UPDATE_OPERATION = 12;
    const ID_WITNESS_CREATE_OPERATION = 13;
    const ID_WITNESS_UPDATE_OPERATION = 14;
    const ID_WITNESS_VOTE_UPDATE_OPERATION = 15;
    const ID_WITNESS_COLLECT_PAY_OPERATION = 16;
    const ID_WITNESS_REPORT_OPERATION = 17;
    const ID_POST_OPERATION = 18;
    const ID_POST_UPDATE_OPERATION = 19;
    const ID_PLATFORM_CREATE_OPERATION = 20;
    const ID_PLATFORM_UPDATE_OPERATION = 21;
    const ID_PLATFORM_VOTE_UPDATE_OPERATION = 22;
    const ID_ACCOUNT_AUTH_PLATFORM_OPERATION = 23;
    const ID_ACCOUNT_CANCEL_AUTH_PLATFORM_OPERATION = 24;
    const ID_ASSET_CREATE_OPERATION = 25;
    const ID_ASSET_UPDATE_OPERATION = 26;
    const ID_ASSET_ISSUE_OPERATION = 27;
    const ID_ASSET_RESERVE_OPERATION = 28;
    const ID_ASSET_CLAIM_FEES_OPERATION = 29;
    const ID_OVERRIDE_TRANSFER_OPERATION = 30;
    const ID_PROPOSAL_CREATE_OPERATION = 31;
    const ID_PROPOSAL_UPDATE_OPERATION = 32;
    const ID_PROPOSAL_DELETE_OPERATION = 33;
    const ID_ACCOUNT_ENABLE_ALLOWED_ASSETS_OPERATION = 34;
    const ID_ACCOUNT_UPDATE_ALLOWED_ASSETS_OPERATION = 35;
    const ID_SCORE_CREATE = 37;
    const ID_LICENSE_LID_CREATE = 41;
    const ID_CUSTOM_VOTE_CREATE_OPERATION = 47;
    const ID_CUSTOM_VOTE_CAST_OPERATION = 48;
    const ID_BALANCE_LOCK_UPDATE_OPERATION = 49;
    const ID_PLEDGE_MINING_UPDATE_OPERATION = 50;
    const ID_PLEDGE_BONUS_COLLECT_OPERATION = 51;
    const ID_LIMIT_ORDER_CREATE_OPERATION = 52;
    const ID_LIMIT_ORDER_CANCEL_OPERATION = 53;

    const ID_CONTRACT_CALL_OPERATION = 60;
    const ID_CONTRACT_UPDATE_OPERATION = 61;

    public static function getOperationIdTypeMap() {
        return [
            OperationTypeId::ID_TRANSER_OPERATION => get_class(new TransferOperation()),
            OperationTypeId::ID_POST_OPERATION => get_class(new PostOperation()),
            OperationTypeId::ID_PLATFORM_CREATE_OPERATION => get_class(new PlatformCreateOperation()),
            OperationTypeId::ID_LICENSE_LID_CREATE => get_class(new LicenseCreateOperation()),
            OperationTypeId::ID_SCORE_CREATE => get_class(new ScoreCreateOperation()),
            OperationTypeId::ID_ACCOUNT_AUTH_PLATFORM_OPERATION => get_class(new AccountAuthPlatformOperation()),
        ];
    }
    public static function getOperationClassNameFromId($operationTypeId) {
        return OperationTypeId::getOperationIdTypeMap()[$operationTypeId];
    }

    public static function getOperationIdFromClassName($operationClassName) {
        foreach (OperationTypeId::getOperationIdTypeMap() as $key => $value) {
            if ($value == $operationClassName) {
                return $key;
            }
        }
        return 0;
    }
}