<?php

/*
 * This file is part of the Quantity package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Quantity;

/**
 * AbstractQuantity
 *
 * A base value object for various types of quantity: weight, length, volume,
 * etc. Made up of an Amount and a Uom
 *
 * @author Tom Haskins-Vaughan <tom@tomhv.uk>
 * @since  0.3.0
 */
abstract class AbstractQuantity
{
    /**
     * $amount
     *
     * @var Amount
     */
    protected $amount;

    /**
     * uom
     *
     * @var Uom
     */
    protected $uom;

    /**
     * Constructor
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @param Amount $amount
     * @param Unit   $unit
     *
     * @throws \Quantity\InvalidAmountException
     */
    public function __construct(Amount $amount, Uom $uom)
    {
        $reflection = new \ReflectionClass($this);
        $subClassName = $reflection->getShortName();
        $uoms = Uom::getUoms();

        // check that the given Uom belongs to the given sub class
        if (!array_key_exists($uom->getName(), $uoms[$subClassName])) {
            throw new Exception\InvalidUomException($uom->getName());
        }

        $this->amount = $amount;
        $this->uom = $uom;
    }

    /**
     * Convenience method for creating Quantity objects
     *
     * e.g. Weight::OZ(10), Quantity::EACH(14)
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        if (count($arguments) > 1) {
            return new static(
                new Amount($arguments[0], $arguments[1]),
                new Uom($method)
            );
        } else {
            return new static(new Amount($arguments[0]), new Uom($method));
        }
    }

    /**
     * Get amount
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get uom
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @return Uom
     */
    public function getUom()
    {
        return $this->uom;
    }

    /**
     * Convert this Weight to a new Unit
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.3.0
     *
     * @param Uom $uom
     *
     * @return mixed
     */
    public function convertTo(Uom $uom)
    {
        // Get the conversion factor as a Fraction
        $conversionFactor = Uom::getConversionFactor(
            $this->getUom(),
            $uom
        );

        // Multiply the amount by the conversion factor and create a new
        // Weight with the new Unit
        return new Weight(
            $this->getAmount()->multiply($conversionFactor),
            $uom
        );
    }
}