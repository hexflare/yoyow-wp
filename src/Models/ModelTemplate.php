<?php


namespace YOYOW\Models;


use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Types\TypeInterface;
use BitWasp\Buffertools\Types\Uint64;

class ModelTemplate  implements \Countable
{
    /**
     * @var TypeInterface[]
     */
    private $template = [];

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * {@inheritdoc}
     * @see \Countable::count()
     * @return int
     */
    public function count(): int
    {
        return count($this->template);
    }

    /**
     * Return an array of type serializers in the template
     *
     * @return Types\TypeInterface[]
     */
    public function getItems(): array
    {
        return $this->template;
    }

    public function getItem($idx)
    {
        return $this->template[$idx];
    }

    /**
     * Add a new TypeInterface to the Template
     *
     * @param  TypeInterface $item
     * @return Template
     */
    public function addItem(TypeInterface $item): ModelTemplate
    {
        $this->template[] = $item;
        return $this;
    }

    /**
     * Write the array of $items to binary according to the template. They must
     * each be an instance of Buffer or implement SerializableInterface.
     *
     * @param  array $items
     * @return BufferInterface
     */
    public function toBytes(array $items): BufferInterface
    {
        if (count($items) != count($this->template)) {
            throw new \RuntimeException('Number of items must match template');
        }

        $binary = '';

        foreach ($this->template as $serializer) {
            $item = array_shift($items);
            if ($item instanceof BaseModelInterface) {
                $binary .= $item->toBytes()->getBinary();
            }  else if ($item instanceof Int64) {
                $binary .= $serializer->writeBits($item);
            } else {
                $binary .= $serializer->write($item);
            }
        }

        return new Buffer($binary);
    }



}