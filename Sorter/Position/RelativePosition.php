<?php

namespace Becklyn\OrderedFormBundle\Sorter\Position;


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
     * @param string $name
     * @param bool   $position
     * @param string $referenceName
     */
    public function __construct (string $name, bool $position, string $referenceName)
    {
        $this->name = $name;
        $this->position = $position;
        $this->referenceName = $referenceName;
    }


    /**
     * @param array $list
     * @return array
     */
    public function moveInList (array &$list) : array
    {
        $this->removeFromList($list);
        $referenceIndex = \array_search($this->referenceName, $list, true);

        if (false === $referenceIndex)
        {
            $list[] = $this->name;
            return $list;
        }

        if ($this->position === self::AFTER)
        {
            $referenceIndex += 1;
        }

        \array_splice($list, $referenceIndex, 0, [$this->name]);
        return $list;
    }


    /**
     * Removes the item from the list
     *
     * @param array $list
     */
    private function removeFromList (array &$list)
    {
        $currentIndex = \array_search($this->name, $list, true);

        if (false === $currentIndex)
        {
            return;
        }

        \array_splice($list, $currentIndex, 1);
    }
}
