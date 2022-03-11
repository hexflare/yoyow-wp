<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\Types\Uint32;
use BitWasp\Buffertools\Types\Uint64;
use BitWasp\Buffertools\Types\Uint8;
use YOYOW\Models\Type\VarDate;

class Statistics extends AbstractBaseModel
{
    public $coreBalance;
    public $prepaid;
    public $csaf;
    public $coreLeaseIn;
    public $coreLeaseOut;
    public $averageCoins;
    public $averageCoinsLastUpdate;
    public $coinSecondsEarned;
    public $coinSecondsEarnedLastUpdate;
    public $canVote;
    public $isVoter;

    public $lastCustomVoteSequence;
    public $totalWitnessPledge;
    public $totalMiningPledge;
    public $totalCommitteeMemberPledge;
    public $totalPlatformPledge;
    public $lockedBalance;
    public $releasingLockedBalance;
    public $lockedReleaseBlock;
    public $releasingWitnessPledge;
    public $witnessReleaseBlock;
    public $releasingCommitteeMemberPledge;
    public $committeePledgeReleaseBlock;
    public $releasingPlatformPledge;
    public $platformReleaseBlock;
    public $uncollectedPledgeBonus;
    public $lastPostSequence;
    public $lastLicenseSequence;

    public function getPropertyMap(): array
    {
        return [
            "core_balance" => "coreBalance",
            "prepaid" => "prepaid",
            "csaf" => "csaf",
            "core_leased_in" => "coreLeaseIn",
            "core_leased_out" => "coreLeaseOut",
            "average_coins" => "averageCoins",
            "average_coins_last_update" => "averageCoinsLastUpdate",
            "coin_seconds_earned" => "coinSecondsEarned",
            "coin_seconds_earned_last_update" => "coinSecondsEarnedLastUpdate",
            "can_vote" => "canVote",
            "is_voter" => "isVoter",
            "last_custom_vote_sequence" => "lastCustomVoteSequence",
            "total_witness_pledge" => "totalWitnessPledge",
            "total_committee_member_pledge" => "totalCommitteeMemberPledge",
            "total_platform_pledge" => "totalPlatformPledge",
            "locked_balance" => "lockedBalance",
            "releasing_locked_balance" => "releasingLockedBalance",
            "total_mining_pledge" => "totalMiningPledge",
            "releasing_committee_member_pledge" => "releasingCommitteeMemberPledge",
            "releasing_platform_pledge" => "releasingPlatformPledge",
            "releasing_witness_pledge" => "releasingWitnessPledge",
            "uncollected_pledge_bonus" => "uncollectedPledgeBonus",
            "locked_balance_release_block_number" => "lockedReleaseBlock",
            "committee_member_pledge_release_block_number" => "committeePledgeReleaseBlock",
            "witness_pledge_release_block_number" => "witnessReleaseBlock",
            "platform_pledge_release_block_number" => "platformReleaseBlock",
            "last_license_sequence" => "lastLicenseSequence",
            "last_post_sequence" => "lastPostSequence",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());

        $temple->addItem(new Uint64());
        $temple->addItem(new VarDate(new Uint32()));
        $temple->addItem(new Uint64());
        $temple->addItem(new VarDate(new Uint32()));
        $temple->addItem(new Uint8());
        $temple->addItem(new Uint8());

        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());

        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint32());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint32());

        $temple->addItem(new Uint64());
        $temple->addItem(new Uint32());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint32());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());
        return $temple;
    }
}