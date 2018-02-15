<?php

namespace Becklyn\OrderedFormBundle\Sorter;

use Becklyn\OrderedFormBundle\Exception\InvalidConfigException;
use Becklyn\OrderedFormBundle\Sorter\Position\RelativePosition;
use Symfony\Component\Form\FormInterface;


class FormFieldSorter
{
    /**
     * The final list of names
     *
     * @var string[]
     */
    private $list = [];


    /**
     * The weighted list of names
     *
     * @var array<int, string[]>
     */
    private $weighted = [];


    /**
     * The explicitly not positioned items
     *
     * @var string[]
     */
    private $notPositioned = [];


    /**
     * The explicit `last` elements
     *
     * @var string[]
     */
    private $explicitLast = [];


    /**
     * The relative positioned elements
     *
     * @var RelativePosition[]
     */
    private $relative = [];


    /**
     *
     * @param FormInterface $form
     * @throws InvalidConfigException
     */
    public function __construct (FormInterface $form)
    {
        foreach ($form as $name => $child)
        {
            $this->add($child);
        }

        $this->buildList();
    }


    /**
     * @param FormInterface $field
     * @throws InvalidConfigException
     */
    private function add (FormInterface $field) : void
    {
        $position = $field->getConfig()->getPosition();
        $fieldName = $field->getName();

        switch (true)
        {
            // don't touch unpositioned fields
            case null === $position:
                $this->notPositioned[] = $fieldName;
                break;

            // add ints as weights
            case \is_int($position):
                $this->weighted[$position][] = $fieldName;
                break;

            // directly push "first" items to the front of the list
            case $position === "first":
                $this->list[] = $fieldName;
                break;

            // collect "last" items
            case $position === "last":
                $this->explicitLast[] = $fieldName;
                break;

            case \is_array($position):
                $this->addRelativePositioned($fieldName, $position);
                break;

            default:
                throw new InvalidConfigException("Invalid value for position: %s");
        }
    }


    /**
     * Add a relative positioned element
     *
     * @param string $fieldName
     * @param array  $position
     * @throws InvalidConfigException
     */
    private function addRelativePositioned (string $fieldName, array $position) : void
    {
        if (count($position) !== 1)
        {
            throw new InvalidConfigException("a position array must only contain exactly one entry.");
        }

        $referenceName = reset($position);
        $direction = key($position);

        if ($referenceName === $fieldName)
        {
            throw new InvalidConfigException(sprintf(
                "A relative positioned form field can't reference itself, in '%s'",
                $fieldName
            ));
        }

        // please see the comment on `moveExplicitlyPositionedItems`
        switch ($direction)
        {
            case "before":
                $this->relative[] = new RelativePosition($fieldName, RelativePosition::BEFORE, $referenceName);
                $this->notPositioned[] = $fieldName;
                break;

            case "after":
                $this->relative[] = new RelativePosition($fieldName, RelativePosition::AFTER, $referenceName);
                $this->notPositioned[] = $fieldName;
                break;

            default:
                throw new InvalidConfigException(sprintf(
                    "Unknown direction for position array: [position => '%s']",
                    $direction
                ));
        }
    }


    /**
     *
     */
    private function buildList () : void
    {
        // first add all not positioned after the first ones
        $this->appendNotPositioned();

        // then sort the weighted ones
        $this->appendWeighted();

        // then add the last ones
        $this->appendExplicitLast();

        // then insert all explicitly inserted items
        $this->moveExplicitlyPositionedItems();
    }


    /**
     * Appends all not explicitly positioned elements
     */
    private function appendNotPositioned () : void
    {
        foreach ($this->notPositioned as $field)
        {
            $this->list[] = $field;
        }
    }


    /**
     * Appends the weighted elements
     */
    private function appendWeighted () : void
    {
        ksort($this->weighted);
        foreach ($this->weighted as $fields)
        {
            foreach ($fields as $field)
            {
                $this->list[] = $field;
            }
        }
    }


    /**
     * Appends the explicit "last" items
     */
    private function appendExplicitLast () : void
    {
        foreach ($this->explicitLast as $field)
        {
            $this->list[] = $field;
        }
    }


    /**
     * Moves all explicitly positioned items
     *
     * First all items are added to the list and later these items are moved
     */
    private function moveExplicitlyPositionedItems () : void
    {
        foreach ($this->relative as $relative)
        {
            $relative->moveInList($this->list);
        }
    }


    /**
     * Returns the sorted list
     *
     * @return iterable
     */
    public function getSorted () : iterable
    {
        return $this->list;
    }
}
