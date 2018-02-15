<?php

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
     * @throws InvalidConfigException
     */
    public function moveInList (array &$list) : array
    {
        $this->removeFromList($list);
        $referenceIndex = \array_search($this->referenceName, $list, true);

        if (false === $referenceIndex)
        {
            throw new InvalidConfigException(sprintf(
                "Can't position field '%s' relative to '%s', as the field wasn't found.",
                $this->name,
                $this->referenceName
            ));
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
