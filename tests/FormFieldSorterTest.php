<?php

namespace Tests\Becklyn\OrderedFormBundle;

use Becklyn\OrderedFormBundle\Exception\InvalidConfigException;
use Becklyn\OrderedFormBundle\Sorter\FormFieldSorter;
use PHPUnit\Framework\TestCase;


class FormFieldSorterTest extends TestCase
{
    public function dataProviderOrder ()
    {
        return [
            // keep order
            [
                ["a" => null, "b" => null],
                ["a", "b"],
            ],
            // first
            [
                ["a" => null, "b" => "first"],
                ["b", "a"],
            ],
            // last
            [
                ["a" => "last", "b" => null],
                ["b", "a"],
            ],
            // last + first
            [
                ["b" => null, "c" => "last", "a" => "first"],
                ["a", "b", "c"],
            ],
            // explicit order
            [
                ["b" => 10, "c" => 20, "a" => 5],
                ["a", "b", "c"],
            ],
            // explicit negative order
            [
                ["b" => -5, "c" => 20, "a" => -10],
                ["a", "b", "c"],
            ],
            // explicit order after unpositioned
            [
                ["b" => 1, "a" => null],
                ["a", "b"],
            ],
            // add every possible case
            [
                ["d" => 10, "a" => "first", "c" => 5, "f" => "last", "e" => ["after" => "d"], "b" => ["before" => "c"]],
                ["a", "b", "c", "d", "e", "f"],
            ],
            // add circular reference
            [
                ["b" => ["after" => "a"], "a" => ["before" => "b"]],
                ["a", "b"],
            ],
            // add circular reference
            [
                ["b" => ["before" => "a"], "a" => ["before" => "b"]],
                ["a", "b"],
            ],
            // keep order in last + first
            [
                ["c" => "last", "d" => "last", "a" => "first", "b" => "first"],
                ["a", "b", "c", "d"],
            ],
        ];
    }


    /**
     * @dataProvider dataProviderOrder
     *
     * @param array $definitions
     * @param array $expectedOrder
     * @throws \Becklyn\OrderedFormBundle\Exception\OrderedFormException
     */
    public function testOrder (array $definitions, array $expectedOrder)
    {
        $sorter = new FormFieldSorter();

        foreach ($definitions as $name => $position)
        {
            $sorter->add($name, $position);
        }

        $actual = $sorter->getSortedFieldNames();

        self::assertEquals($expectedOrder, \array_values($actual));
    }




    public function dataProviderInvalidConfig ()
    {
        return [
            // invalid value
            [
                ["test" => "invalid"],
                InvalidConfigException::class,
                "~Invalid value for position~"
            ],
            [
                ["test" => false],
                InvalidConfigException::class,
                "~Invalid value for position~"
            ],
            // invalid array structure
            [
                ["test" => ["a", "b"]],
                InvalidConfigException::class,
                "~a position array must only contain exactly one entry~"
            ],
            [
                ["test" => []],
                InvalidConfigException::class,
                "~a position array must only contain exactly one entry~"
            ],
            // invalid array key
            [
                ["test" => ["invalid" => "before"]],
                InvalidConfigException::class,
                "~Unknown direction for position array~"
            ],
            // no self reference
            [
                ["test" => ["before" => "test"]],
                InvalidConfigException::class,
                "~A relative positioned form field can't reference itself~"
            ],
            [
                ["test" => ["after" => "test"]],
                InvalidConfigException::class,
                "~A relative positioned form field can't reference itself~"
            ],
            // reference unknown field
            [
                ["test" => ["after" => "unknown"]],
                InvalidConfigException::class,
                "~Can't position field '.*?' relative to '.*?', as the field wasn't found\\.~"
            ],
            [
                ["test" => ["before" => "unknown"]],
                InvalidConfigException::class,
                "~Can't position field '.*?' relative to '.*?', as the field wasn't found\\.~"
            ],
        ];
    }


    /**
     * @dataProvider dataProviderInvalidConfig
     *
     * @param array  $definitions
     * @param string $exception
     * @param string $messageRegExp
     * @throws \Becklyn\OrderedFormBundle\Exception\OrderedFormException
     */
    public function testInvalidConfig (array $definitions, string $exception, string $messageRegExp)
    {
        $this->expectException($exception);
        $this->expectExceptionMessageRegExp($messageRegExp);

        $sorter = new FormFieldSorter();

        foreach ($definitions as $name => $position)
        {
            $sorter->add($name, $position);
        }

        $sorter->getSortedFieldNames();
    }
}
