<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Types\TypeInterface;

interface BaseModelInterface extends TypeInterface
{
    public function toBytes(): BufferInterface;

    public function toString();

    public function pase(array $input);

    public function getPropertyMap(): array;

    public function getTemple(): ModelTemplate;

}