<?php


namespace YOYOW\Models\Type;

use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;
use BitWasp\Buffertools\Types\AbstractType;
use BitWasp\Buffertools\Types\Uint32;


class VarDate extends AbstractType
{
    private $varInt;

    public function __construct(Uint32 $varInt)
    {
        $this->varint = $varInt;
        parent::__construct($varInt->getByteOrder());
    }

    public function write($integer): string
    {
        return $this->varint->write($integer);
    }

    /**
     * @param Parser $parser
     * @return BufferInterface
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     */
    public function read(Parser $parser): BufferInterface
    {
         return $this->varint->read($parser);
    }
}