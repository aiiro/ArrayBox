<?php

namespace ArrayBox;

/**
 * Class Helper
 *
 * @package ArrayBox
 */
class Helper
{
    /**
     * Sort two dimensional array using the given $first, $second parameter.
     *
     * @param array $two_dimensional_array
     * @param $first
     * @param $first_order
     * @param $second
     * @param $second_order
     * @return array
     */
    public static function sort2Dimensional($two_dimensional_array, $first, $first_order, $second, $second_order)
    {
        $copy = $two_dimensional_array;

        $first_column = [];
        $second_column = [];

        // Change the array of rows to array of columns.
        foreach ($two_dimensional_array as $key => $row) {
            $first_column[$key] = $row[$first];
            $second_column[$key] = $row[$second];
        }
        array_multisort($first_column, $first_order, $second_column, $second_order, $copy);

        return $copy;
    }

    /**
     * Find duplications in multi dimensional array.
     *
     * @param array $multi_dimensional_array Multi dimensional array
     * @return array $duplicate_values Duplicate arrays
     */
    public static function duplicatesInMultiDimensional($multi_dimensional_array)
    {
        $serialized = array_map('serialize', $multi_dimensional_array);
        $values_counts = array_count_values($serialized);

        $duplicate_values = [];
        foreach ($values_counts as $value => $count) {
            if ($count > 1) {
                $duplicate_values[] = unserialize($value);
            }
        }

        return $duplicate_values;
    }

}