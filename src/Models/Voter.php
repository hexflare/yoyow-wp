<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\Types\Uint32;
use BitWasp\Buffertools\Types\Uint64;
use BitWasp\Buffertools\Types\Uint8;
use BitWasp\Buffertools\Types\VarInt;
use BitWasp\Buffertools\Types\VarString;
use YOYOW\Models\Type\VarDate;
use YOYOW\Models\Type\YYWArray;

class Voter extends AbstractBaseModel
{
    public $id;
    public $uid;
    public $sequence;
    public $isValid;
    public $votes;
    public $votesLastUpdate;
    public $effectiveVotes;
    public $effectiveVotesLastUpdate;
    public $effectoveVotesNextUpdateBlock;
    public $proxyUid;
    public $proxySequence;
    public $effectiveLastVoteBlock;
    public $numberOfWitnessesvoted;
    public $proxyLastVoteUpdate;

    public function getPropertyMap(): array
    {
        return [
            "id" => "id",
            "uid" => "uid",
            "sequence" => "sequence",
            "is_valid" => "isValid",
            "votes_last_update" => "votesLastUpdate",
            "effective_votes" => "effectiveVotes",
            "effective_votes_last_update" => "effectiveVotesLastUpdate",
            "proxy_uid" => "proxyUid",
            "effective_last_vote_block" => "effectiveLastVoteBlock",
            "number_of_witnesses_voted" => "numberOfWitnessesvoted",
            "proxy_last_vote_block" => "proxyLastVoteUpdate",
        ];
    }

    public function getTemple(): ModelTemplate
    {
        $temple = new ModelTemplate();
        $temple->addItem(new VarString(new VarInt()));
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint32());
        $temple->addItem(new Uint8());
        $temple->addItem(new Uint64());
        $temple->addItem(new VarDate(new Uint32()));
        $temple->addItem(new Uint64());
        $temple->addItem(new VarDate(new Uint32()));
        $temple->addItem(new Uint64());
        $temple->addItem(new Uint64());

        $temple->addItem(new Uint32());
        $temple->addItem(new Uint32());
        $temple->addItem(new Uint32());

        $temple->addItem(new YYWArray(new Uint32()));
        return $temple;
    }
}