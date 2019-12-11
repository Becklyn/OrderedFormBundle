<?php declare(strict_types=1);

namespace Becklyn\OrderedFormBundle\Sorter\Position;

use Becklyn\OrderedFormBundle\Exception\InvalidConfigException;

class RelativePosition
{
    public const BEFORE = true;
    public const AFTER = false;

    /**
     * @var string
     */
    protected $name;


    /**
     * @var bool
     */
    private $position;


    /**
     * @var string
     */
    protected $referenceName;


    /**
     */
    public function __construct (string $name, bool $position, string $referenceName)
    {
        $this->name = $name;
        $this->position = $position;
        $this->referenceName = $referenceName;
    }


    /**
     * @throws InvalidConfigException
     */
    public function moveInList (array &$list) : array
    {
        $this->removeFromList($list);
        $referenceIndex = \array_search($this->referenceName, $list, true);

        if (false === $referenceIndex)
        {
            throw new InvalidConfigException(\sprintf(
                "Can't position field '%s' relative to '%s', as the field wasn't found.",
                $this->name,
                $this->referenceName
            ));
        }

        if (self::AFTER === $this->position)
        {
            ++$referenceIndex;
        }

        \array_splice($list, $referenceIndex, 0, [$this->name]);
        return $list;
    }


    /**
     * Removes the item from the list
     */
    private function removeFromList (array &$list) : void
    {
        $currentIndex = \array_search($this->name, $list, true);

        if (false === $currentIndex)
        {
            return;
        }

        \array_splice($list, $currentIndex, 1);
    }
}
