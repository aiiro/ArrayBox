<?php

namespace Tests\ArrayBox;

use ArrayBox\ArrayBox;

/**
 * Class ArrayBoxTest
 *
 * @package Tests\ArrayBox
 */
class ArrayBoxTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @covers new ArrayBox()
     */
    public function array_box_instance_contains_the_array()
    {
        /** @var ArrayBox $instance */
        $instance = new ArrayBox(1, 2, 3);
        $instance2 = new ArrayBox([4, 5, 6]);
        $instance3 = new ArrayBox(7, 8, ['foo', 'bar']);
        $instance4 = new ArrayBox();

        $this->assertEquals([1, 2, 3], $instance->getValues());
        $this->assertEquals([4, 5, 6], $instance2->getValues());
        $this->assertEquals([7, 8, ['foo', 'bar']], $instance3->getValues());
        $this->assertEquals([], $instance4->getValues());
    }

    /**
     * @test
     * @covers       ArrayBox::sort2Dimensional()
     * @dataProvider sort2DimensionalDataProvider
     * @param $data
     * @param $expected
     */
    public function sort_two_dimensional_array($data, $expected)
    {
        $instance = new ArrayBox($data);
        $this->assertEquals($expected, $instance->sort2Dimensional('volume', SORT_DESC, 'edition', SORT_ASC));
    }

    /**
     * data provider
     * @return array
     */
    public function sort2DimensionalDataProvider()
    {
        return [
            [
                'data' => [
                    ['volume' => 67, 'edition' => 2, 'impression' => 1],
                    ['volume' => 86, 'edition' => 1, 'impression' => 2],
                    ['volume' => 85, 'edition' => 6, 'impression' => 3],
                    ['volume' => 98, 'edition' => 2, 'impression' => 4],
                    ['volume' => 86, 'edition' => 3, 'impression' => 5],
                    ['volume' => 86, 'edition' => 2, 'impression' => 6],
                    ['volume' => 67, 'edition' => 7, 'impression' => 7],
                ],
                'expected' => [
                    ['volume' => 98, 'edition' => 2, 'impression' => 4],
                    ['volume' => 86, 'edition' => 1, 'impression' => 2],
                    ['volume' => 86, 'edition' => 2, 'impression' => 6],
                    ['volume' => 86, 'edition' => 3, 'impression' => 5],
                    ['volume' => 85, 'edition' => 6, 'impression' => 3],
                    ['volume' => 67, 'edition' => 2, 'impression' => 1],
                    ['volume' => 67, 'edition' => 7, 'impression' => 7],
                ],
            ],
        ];
    }

    /**
     * @test
     * @covers ArrayBox::duplicatesInMultiDimensional
     */
    public function find_the_duplication_in_2d_array()
    {
        $data = [
            ['volume' => 1, 'edition' => 1],
            ['volume' => 1, 'edition' => 1],
            ['volume' => 1, 'edition' => 1],
            ['volume' => 2, 'edition' => 1],
            ['volume' => 2, 'edition' => 1],
            ['volume' => 2, 'edition' => 2],
            ['volume' => 2, 'edition' => 3],
        ];
        $expected = [
            ['volume' => 1, 'edition' => 1],
            ['volume' => 2, 'edition' => 1],
        ];

        $instance = new ArrayBox($data);

        $this->assertEquals($expected, $instance->duplicatesInMultiDimensional());
    }

    /**
     * @test
     * @covers ArrayBox::duplicatesInMultiDimensional
     */
    public function find_the_duplication_in_3d_array()
    {
        $data = [
            [
                ['volume' => 1, 'edition' => 1],
                ['volume' => 1, 'edition' => 2],
            ],
            [
                ['volume' => 1, 'edition' => 1],
                ['volume' => 1, 'edition' => 2],
            ],
            ['volume' => 2, 'edition' => 1],
            ['volume' => 2, 'edition' => 1],
            ['volume' => 2, 'edition' => 2],
            ['volume' => 2, 'edition' => 3],
            'foo_key' => [
                ['volume' => 3, 'edition' => 1],
                ['volume' => 3, 'edition' => 2],
            ],
            'bar_key' => [
                ['volume' => 3, 'edition' => 1],
                ['volume' => 3, 'edition' => 2],
            ],
        ];
        $expected = [
            [
                ['volume' => 1, 'edition' => 1],
                ['volume' => 1, 'edition' => 2],
            ],
            ['volume' => 2, 'edition' => 1],
            [
                ['volume' => 3, 'edition' => 1],
                ['volume' => 3, 'edition' => 2],
            ],
        ];
        $instance = new ArrayBox($data);

        $this->assertEquals($expected, $instance->duplicatesInMultiDimensional());
    }

    /**
     * @test
     * @covers ArrayBox::between()
     */
    public function retrieve_values_within_the_given_range()
    {
        $array_box = new ArrayBox([ 'a', 'b', 'c', 'd', 'e', 'f' ]);

        $this->assertEquals(['b', 'c', 'd', 'e'], $array_box->between(1, 4));
        $this->assertEquals(['a', 'b', 'c', 'd'], $array_box->between(0, 3));
        $this->assertEquals(['c', 'd', 'e', 'f'], $array_box->between(2, -1));

        $this->assertEquals(['a', 'b', 'c', 'd'], $array_box->between(null, 3));
        $this->assertEquals(['a', 'b', 'c', 'd', 'e'], $array_box->between(null, -2));

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f'], $array_box->between(0, null));
        $this->assertEquals(['c', 'd', 'e', 'f'], $array_box->between(2, null));
        $this->assertEquals(['f'], $array_box->between(-1, null));

    }

    /**
     * @test
     * @covers ArrayBox::except()
     * @dataProvider exceptDataProvider
     * @param $value
     * @param $expected
     */
    public function retrieve_values_from_array_except_for_the_given_one($value, $expected)
    {
        $instance = new ArrayBox([1, true, 1, null, 'foo', false, 'bar']);

        $this->assertEquals($expected, $instance->except($value));
    }

    /**
     * data provider
     * @return array
     */
    public function exceptDataProvider()
    {
        return [
            'number' => [
                'value'    => 1,
                'expected' => [true, null, 'foo', false, 'bar'],
            ],
            'string' => [
                'value'    => 'foo',
                'expected' => [1, true, 1, null, false, 'bar'],
            ],
            'true' => [
                'value'    => true,
                'expected' => [1, 1, null, 'foo', false, 'bar'],
            ],
            'false' => [
                'value'    => false,
                'expected' => [1, true, 1, null, 'foo', 'bar'],
            ],
            'null' => [
                'value'    => null,
                'expected' => [1, true, 1, 'foo', false, 'bar'],
            ],
        ];
    }

    /**
     * @test
     * @covers ArrayBox::except()
     */
    public function it_can_preserve_original_key()
    {
        $instance = new ArrayBox([
            'alpha'   => 1,
            'bravo'   => true,
            'charlie' => 1,
            'delta'   => null,
            'echo'    => 'foo',
            'foxtrot' => false,
            'golf'    => 'bar'
        ]);

        $this->assertEquals([
            'bravo'   => true,
            'delta'   => null,
            'echo'    => 'foo',
            'foxtrot' => false,
            'golf'    => 'bar'
        ], $instance->except(1, true));
    }

    /**
     * @test
     * @covers       ArrayBox::only()
     * @dataProvider onlyDataProvider
     * @param $value
     * @param $expected
     */
    public function retrieve_the_specified_values_from_array($value, $expected)
    {
        // Prepare
        $instance = new ArrayBox([1, true, 1, null, 'foo', false, 'bar']);

        $this->assertEquals($expected, $instance->only($value));
    }

    /**
     * data provider
     * @return array
     */
    public function onlyDataProvider()
    {
        return [
            'number' => [
                'value'    => 1,
                'expected' => [1, 1],
            ],
            'string' => [
                'value'    => 'foo',
                'expected' => ['foo'],
            ],
            'true' => [
                'value'    => true,
                'expected' => [true],
            ],
            'false' => [
                'value'    => false,
                'expected' => [false],
            ],
            'null' => [
                'value'    => null,
                'expected' => [null],
            ],
        ];
    }
    
    /**
     * @test
     * @covers ArrayBox::only()
     */
    public function only_method_can_preserve_original_key()
    {
        $instance = new ArrayBox([
            'alpha'   => 1,
            'bravo'   => true,
            'charlie' => 1,
            'delta'   => null,
            'echo'    => 'foo',
            'foxtrot' => false,
            'golf'    => 'bar'
        ]);

        $this->assertEquals([
            'alpha'   => 1,
            'charlie' => 1,
        ], $instance->only(1, true));
    }
    
}
