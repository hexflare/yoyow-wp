<?php


namespace YOYOW\Utils;


class TransactionHelper
{
    public static function prepareTransaction($transaction) {
        TransactionHelper::handleFee($transaction);
        TransactionHelper::handleRefBlock($transaction);
    }

    public static function handleFee($transaction) {
        $requireFees = YOYOWApi::getRequireFeeWithOperations($transaction->operations->data);
        for ($i = 0; $i < count($transaction->operations->data); $i++) {
            $operationType = $transaction->operations->data[$i];
            $fee = $requireFees[$i];
            $operation = $operationType->content;
            $operation->updateFee($fee->minFee, $fee->minRealFee);
        }
    }

    public static function handleRefBlock($transaction) {
        $blockObject = YOYOWApi::getBlockObject();
        $transaction->setHeaderBlockId($blockObject->headBlockId)->setTransactionTime($blockObject->time);
    }

    public static function broadcast($transaction) {
        $res = YOYOWApi::broadCastTransaction($transaction);
        return $res;
    }

}