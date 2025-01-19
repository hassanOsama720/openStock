<?php

namespace App\Enums;

use Prophecy\Exception\Doubler\MethodNotFoundException;

/**
 * Class Base
 * @package App\Enums
 */
abstract class Base
{
    public $reflection;

    public function __construct()
    {
        try {
            $this->reflection = new \ReflectionClass(static::class);
        } catch (\ReflectionException $e) {
            report($e);
        }
    }

    public static function __callStatic($method, $args)
    {
        if ($method == 'all') {
            return (new static())->all();
        }

        if ($method == 'nameFor') {
            return (new static())->nameFor(...$args);
        }

        if ($method == 'toArray') {
            return (new static())->toArray();
        }

        if ($method == 'forApi') {
            return (new static())->forApi();
        }

        if ($method == 'slug') {
            return (new static())->slug(...$args);
        }

        return MethodNotFoundException::class;
    }

    /**
     * returns a string of constants integer values.
     *
     * @return string
     */
    protected function all() : string
    {
        $constantsArray = $this->reflection->getConstants();

        return implode(',', array_values($constantsArray));
    }

    /**
     * returns the array representation of all constants
     *
     * @return array
     */
    protected function toArray(): array
    {
        return $this->reflection->getConstants();
    }

    /**
     * returns the string representation from an integer.
     *
     * @param int $integer
     * @return string|null
     */
    protected function nameFor(int $integer)
    {
        $flippedConstantsArray = array_flip($this->reflection->getConstants());

        if (isset($flippedConstantsArray[$integer])) {
            $removeUnderScores = str_replace('_', ' ', $flippedConstantsArray[$integer]);
            return __(ucfirst(strtolower($removeUnderScores)));
        }

        return null;
    }

    /**
     * Convert constants to array of objects when used by controllers
     * e.g. [{id: 1, name: 'new'}, ...]
     *
     * @return array
     */
    protected function forApi()
    {
        $constants = $this->reflection->getConstants();
        $newForm = [];

        foreach ($constants as $key => $val) {
            $newForm[] = ['id' => $val, 'name' => __(strtolower($key))];
        }

        return $newForm;
    }

    protected function slug($value): string
    {
        $constants = array_flip($this->reflection->getConstants());

        return strtolower($constants[$value]);
    }
}
