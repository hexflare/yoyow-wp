<?php

namespace YOYOW\Address;

use BitWasp\Buffertools\BufferInterface;

interface AddressInterface {
    /**
     * @return string
     */
    public function getAddress(): string;

    /**
     * @return BufferInterface
     */
    public function getHash(): BufferInterface;

    /**
     * @return ScriptInterface
     */
//    public function getPubKey(): ScriptInterface;

}