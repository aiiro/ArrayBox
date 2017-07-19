<?php

namespace ArrayBox\Tests;

use ArrayBox\Helper;
use PHPUnit\Framework\TestCase;

/**
 * Class HelperTest
 *
 * @package ArrayBox\Tests
 */
class HelperTest extends TestCase
{
    /**
     * @test
     * @covers       Helper::sort2Dimensional()
     * @dataProvider sort2DimensionalDataProvider
     * @param $data
     * @param $expected
     */
    public function sort_two_dimensional_array($data, $expected)
    {
        $this->assertEquals($expected, Helper::sort2Dimensional($data, 'volume', SORT_DESC, 'edition', SORT_ASC));
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
     * @covers Helper::duplicatesInMultiDimensional
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

        $this->assertEquals($expected, Helper::duplicatesInMultiDimensional($data));
    }

    /**
     * @test
     * @covers Helper::duplicatesInMultiDimensional
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

        $this->assertEquals($expected, Helper::duplicatesInMultiDimensional($data));
    }

    /**
     * @test
     * @covers Helper::between()
     */
    public function retrieve_values_within_the_given_range()
    {
        // Prepare
        $data = [
            'a', 'b', 'c', 'd', 'e', 'f',
        ];

        $this->assertEquals(['b', 'c', 'd', 'e'], Helper::between($data, 1, 4));
        $this->assertEquals(['a', 'b', 'c', 'd'], Helper::between($data, 0, 3));
        $this->assertEquals(['c', 'd', 'e', 'f'], Helper::between($data, 2, -1));

        $this->assertEquals(['a', 'b', 'c', 'd'], Helper::between($data, null, 3));
        $this->assertEquals(['a', 'b', 'c', 'd', 'e'], Helper::between($data, null, -2));

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f'], Helper::between($data, 0, null));
        $this->assertEquals(['c', 'd', 'e', 'f'], Helper::between($data, 2, null));
        $this->assertEquals(['f'], Helper::between($data, -1, null));

    }

    /**
     * @test
     * @covers Helper::except()
     * @dataProvider exceptDataProvider
     * @param $value
     * @param $expected
     */
    public function retrieve_values_from_array_except_for_the_given_one($value, $expected)
    {
        $data = [1, true, 1, null, 'foo', false, 'bar'];

        $this->assertEquals($expected, Helper::except($data, $value));
    }

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
     * @covers Helper::except()
     */
    public function except_can_preserve_original_key()
    {
        $data = [
            'alpha'   => 1,
            'bravo'   => true,
            'charlie' => 1,
            'delta'   => null,
            'echo'    => 'foo',
            'foxtrot' => false,
            'golf'    => 'bar'
        ];

        $this->assertEquals([
            'bravo'   => true,
            'delta'   => null,
            'echo'    => 'foo',
            'foxtrot' => false,
            'golf'    => 'bar'
        ], Helper::except($data, 1, true));
    }

}
