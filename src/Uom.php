<?php

/*
 * This file is part of the Quantity package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Quantity;

use \Yeriki\Fractions\Fraction;

/**
 * Uom (Unit of Measure)
 *
 * @author Tom Haskins-Vaughan <tom@tomhv.uk>
 * @since  0.3.0
 */
class Uom
{
    /**
     * uoms (Units of Measure)
     *
     * @var array
     */
    private static $uoms;

    /**
     * conversions
     *
     * An array of conversion factors
     *
     * @var array
     */
    private static $conversions;

    /**
     * name
     *
     * @var string
     */
    private $name;

    /**
     * Constructor
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @param string $name Name of unit, e.g. OZ, LB, KG
     *
     * @throws InvalidUomException
     */
    public function __construct($name)
    {
        foreach (static::getUoms() as $type) {
            if (array_key_exists($name, $type)) {
                $this->name = $name;

                return;
            }
        }

        // can't find Uom by name
        throw new Exception\InvalidUomException($name);
    }

    /**
     * __toString
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.6.0
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Convenience method for creating Uom objects
     *
     * e.g. Uom::OZ(), Uom::LB();
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Uom
     */
    public static function __callStatic($method, $arguments)
    {
        return new Uom($method);
    }

    /**
     * Get name
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the conversion factor between two Units of Weight
     *
     * e.g. from LB to OZ = 16
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @param Uom $from
     * @param Uom $to
     *
     * @return Fraction
     */
    public static function getConversionFactor(Uom $from, Uom $to)
    {
        if(!isset(static::$conversions)) {
            static::$conversions = json_decode(
                utf8_encode(
                    file_get_contents(__DIR__.'/conversions.json')
                ),
                true
            );
        }

        $conversionValues = static::$conversions[$from->getName()][$to->getName()];

        if (count($conversionValues) > 1) {
            return new Fraction(
                $conversionValues[0],
                $conversionValues[1]
            );
        } else {
            return new Fraction($conversionValues[0]);
        }
    }

    /**
     * Get uoms
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @return array
     */
    public static function getUoms()
    {
        if(!isset(static::$uoms)) {
            static::$uoms = json_decode(
                utf8_encode(
                    file_get_contents(__DIR__.'/uoms.json')
                ),
                true
            );
        }

        return static::$uoms;
    }
}
