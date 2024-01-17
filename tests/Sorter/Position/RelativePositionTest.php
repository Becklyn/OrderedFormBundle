<?php

namespace Tests\Becklyn\OrderedFormBundle\Sorter\Position;

use Becklyn\OrderedFormBundle\Sorter\Position\RelativePosition;
use Becklyn\OrderedFormBundle\Exception\InvalidConfigException;
use PHPUnit\Framework\TestCase;

/**
 * This class tests the 'moveInList' method of 'RelativePosition' class.
 * The 'moveInList' method takes an array and modifies its order based on the relative position criteria of an item.
 */
class RelativePositionTest extends TestCase
{
    /**
     * Test 'moveInList' method when the reference item is present in the list.
     * The 'moveInList' should successfully move the item before or after the reference item.
     *
     * @small
     */
    public function testMoveInListWithReferencePresent(): void
    {
        $list = ['item1', 'item2', 'item3'];

        $relativePosition = new RelativePosition('item1', RelativePosition::AFTER, 'item2');
        $result = $relativePosition->moveInList($list);

        $this->assertEquals(['item2', 'item1', 'item3'], $result);

        $relativePosition = new RelativePosition('item2', RelativePosition::BEFORE, 'item3');
        $result = $relativePosition->moveInList($list);

        $this->assertEquals(['item1', 'item2', 'item3'], $result);
    }

    /**
     * Test 'moveInList' method when the reference item is not present in the list.
     * The 'moveInList' should throw InvalidConfigException.
     *
     * @small
     */
    public function testMoveInListWithReferenceAbsent(): void
    {
        $this->expectException(InvalidConfigException::class);

        $list = ['item1', 'item2', 'item3'];

        $relativePosition = new RelativePosition('item1', RelativePosition::AFTER, 'item4');
        $relativePosition->moveInList($list);
    }

    /**
     * Test 'moveInList' method when the reference item is not present in the list.
     * The 'moveInList' should return the modified list with the item placed at the specified position.
     *
     * @small
     */
    public function testMoveInListWithNameAbsent(): void
    {
        $list = ['item1', 'item2', 'item3'];

        $relativePosition = new RelativePosition('item4', RelativePosition::AFTER, 'item1');
        $result = $relativePosition->moveInList($list);

        $this->assertEquals(['item1', 'item4', 'item2', 'item3'], $result);
    }
}
