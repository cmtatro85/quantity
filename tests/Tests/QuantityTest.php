<?php

/*
 * This file is part of the Quantity package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Quantity\Tests;

use Quantity\Amount,
    Quantity\Quantity,
    Quantity\Quantity\Unit;

/**
 * QuantityTest
 *
 * @author Tom Haskins-Vaughan <tom@tomhv.uk>
 * @since  0.1.0
 */
class QuantityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test constructor
     *
     * @author Tom Haskins-Vaughan <tom@tomhv.uk>
     * @since  0.1.0
     */
    public function testConstructor()
    {
        $quantity = new Quantity(new Amount(10), new Unit('EACH'));

        $this->assertEquals(new Amount(10), $quantity->getAmount());
        $this->assertSame('EACH', $quantity->getUnit()->getName());
    }
}
