<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Types\Uint32;
use BitWasp\Buffertools\Types\VarInt;
use BitWasp\Buffertools\Types\VarString;
use YOYOW\Models\Type\VarDate;

class YYWObject extends AbstractBaseModel
{
    public $budgetPool;
    public $currentAslot;
    public $currentWitness;
    public $headBlockId;


    public $headBlockNumber;
    public $objectId;
    public $lastIrreversibleBlockNum;
    public $nextBudgetAdjustBlock;

    public $nextCommitteeProposalNumber;
    public $nextCommitteeUpdateBlock;
    public $recentlyMissedCount;
    public $recentSlotsFilled;

    public $time;
    public $totalBudgetPerBlock;

    public function getPropertyMap(): array
    {
        return [
            "budget_pool" => "budgetPool",
            "current_aslot" => "currentAslot",
            "current_witness" => "currentWitness",
            "head_block_id" => "headBlockId",

            "head_block_number" => "headBlockNumber",
            "id" => "objectId",
            "last_irreversible_block_num" => "lastIrreversibleBlockNum",
            "next_budget_adjust_block" => "nextBudgetAdjustBlock",

            "next_committee_proposal_number" => "nextCommitteeProposalNumber",
            "next_committee_update_block" => "nextCommitteeUpdateBlock",
            "recently_missed_count" => "recentlyMissedCount",
            "recent_slots_filled" => "recentSlotsFilled",

            "time" => "time",
            "total_budget_per_block" => "totalBudgetPerBlock",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new VarString(new VarInt()));

        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new VarString(new VarInt()));
        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new Uint32(ByteOrder::LE));

        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new Uint32(ByteOrder::LE));
        $temple->addItem(new VarString(new VarInt(ByteOrder::LE)));
        $temple->addItem(new Uint32(ByteOrder::LE));

        $temple->addItem(new VarDate(new Uint32(ByteOrder::LE)));
        $temple->addItem(new Uint32(ByteOrder::LE));
        return $temple;
    }
}