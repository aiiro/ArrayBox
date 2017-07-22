<?php

namespace ArrayBox;

use ArrayBox\Exceptions\InvalidKeyException;

/**
 * Class ArrayBox
 *
 * @package ArrayBox
 */
class ArrayBox
{
    /** @var array */
    protected $values = [];

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
                $this->values[] = $value;
            }
        }

        if (is_array($values[0])) {
            $this->values = $values[0];
        }
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Add passed value to the instance $values variable.
     *
     * @param $value
     * @param null|string|int $key
     */
    public function add($value, $key=null)
    {
        if (!is_null($key)) {
            if (!is_string($key) && !is_numeric($key)) {
                throw new InvalidKeyException();
            }
            $this->values[$key] = $value;
        } else {
            $this->values[] = $value;
        }
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
        $copy = $this->copy()->values;

        $first_column = [];
        $second_column = [];

        // Change the array of rows to array of columns.
        foreach ($this->values as $key => $row) {
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
        $serialized = array_map('serialize', $this->values);
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
            return $this->values;
        }

        if (empty($from)) {
            return array_slice($this->values, $from, $to + 1);
        }

        if ($to < 0) {
            return array_slice($this->values, $from, count($this->values));
        }

        return array_slice($this->values, $from, $to);
    }

    /**
     * Retrieve the values except for the given value.
     *
     * @param $value
     * @param bool $preserve_key
     * @return array
     */
    public function except($value, $preserve_key=false)
    {
        $filtered = array_filter($this->values, function ($element) use ($value) {
            return ($element !== $value) ? true : false;
        });

        return ($preserve_key) ? $filtered : array_values($filtered);
    }

    /**
     * Retrieve the specified values.
     *
     * @param $value
     * @param bool $preserve_key
     * @return array
     */
    public function only($value, $preserve_key=false)
    {
        $filtered = array_filter($this->values, function ($element) use ($value) {
            return ($element === $value) ? true : false;
        });

        return ($preserve_key) ? $filtered : array_values($filtered);
    }

    /**
     * Check if the instance contains the passed value.
     *
     * @param $value
     * @return bool
     */
    public function contains($value)
    {
        foreach ($this->values as $item) {
            if ($item === $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * Convert the properties to array.
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

}