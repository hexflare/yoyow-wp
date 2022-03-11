<?php


namespace YOYOW\Models\Type;


use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;
use BitWasp\Buffertools\Types\AbstractType;

class Pack extends AbstractType
{

    public function write($integer): string
    {
        $binary = '';
        $lValue = $integer;
        do {
            $b = $lValue & 0x7f;
            $lValue >>= 7;
            if ($lValue > 0) {
                $b |= (1 << 7);
            }
            $binary .= chr($b);
        } while ($lValue > 0);
        return $binary;
    }

    /**
     * @param Parser $parser
     * @return BufferInterface
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     */
    public function read(Parser $parser): BufferInterface
    {
        return Buffer();
    }
}