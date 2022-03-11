<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\Types\Uint64;
use YOYOW\Models\Type\YYWArray;

class FullAccount extends AbstractBaseModel
{
    public $account;
    public $statistics;
    public $voter;
    public $platformVotes;
    public $assets;
    public $witnessVotes;
    public $committeeMemberVotes;

    public function getPropertyMap(): array
    {
        return [
            "account" => "account",
            "statistics" => "statistics",
            "voter" => "voter",
            "platform_votes" => "platformVotes",
            "assets" => "assets",
            "witness_votes" => "witnessVotes",
            "committee_member_votes" => "committeeMemberVotes",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new Account());
        $temple->addItem(new Statistics());
        $temple->addItem(new Voter());
        $temple->addItem(new YYWArray(new Uint64()));
        $temple->addItem(new YYWArray(new Uint64()));
        $temple->addItem(new YYWArray(new Uint64()));
        $temple->addItem(new YYWArray(new Uint64()));
        return $temple;
    }
}