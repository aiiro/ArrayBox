<?php

namespace ArrayBox;

/**
 * Class ArrayBox
 *
 * @package ArrayBox
 */
class ArrayBox
{
    /** @var array */
    protected $value = [];

    /**
     * ArrayBox constructor.
     *
     * @param array ...$values
     */
    public function __construct(...$values)
    {
        if (count($values) === 0) {
            return;
        }

        if (count($values) > 1) {
            foreach ($values as $value) {
                $this->value[] = $value;
            }
        }

        if (is_array($values[0])) {
            $this->value = $values[0];
        }
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return ArrayBox
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * Sort two dimensional array using the given $first, $second parameter.
     *
     * @param $first
     * @param $first_order
     * @param $second
     * @param $second_order
     * @return array
     */
    public function sort2Dimensional($first, $first_order, $second, $second_order)
    {
        $copy = $this->copy()->value;

        $first_column = [];
        $second_column = [];

        // Change the array of rows to array of columns.
        foreach ($this->value as $key => $row) {
            $first_column[$key] = $row[$first];
            $second_column[$key] = $row[$second];
        }
        array_multisort($first_column, $first_order, $second_column, $second_order, $copy);

        return $copy;
    }

    /**
     * Find duplications in multi dimensional array.
     *
     * @return array $duplicate_values Duplicate arrays
     */
    public function duplicatesInMultiDimensional()
    {
        $serialized = array_map('serialize', $this->value);
        $values_counts = array_count_values($serialized);

        $duplicate_values = [];
        foreach ($values_counts as $value => $count) {
            if ($count > 1) {
                $duplicate_values[] = unserialize($value);
            }
        }

        return $duplicate_values;
    }

    /**
     * Retrieve the values within the given range.
     *
     * @param null|int $from
     * @param null|int $to
     * @return array
     */
    public function between($from=null, $to=null)
    {
        if (empty($from) && empty($to)) {
            return $this->value;
        }

        if (empty($from)) {
            return array_slice($this->value, $from, $to + 1);
        }

        if ($to < 0) {
            return array_slice($this->value, $from, count($this->value));
        }

        return array_slice($this->value, $from, $to);
    }

    /**
     * Retrieve the values except for the given value.
     *
     * @param $null
     * @param bool $preserve_key
     * @return array
     */
    public function except($null, $preserve_key=false)
    {
        $filtered = array_filter($this->value, function ($element) use ($null) {
            return ($element !== $null) ? true : false;
        });

        return ($preserve_key) ? $filtered : array_values($filtered);
    }

}