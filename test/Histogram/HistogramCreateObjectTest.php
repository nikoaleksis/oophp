<?php

namespace Niko\Histogram;

use PHPUnit\Framework\TestCase;

class HistogramCreateObjectTest extends TestCase
{
    /**
     *  Create object and test that the object has the expected values.
     */
    public function testCreateObject()
    {
        $histogram = new Histogram();
        $this->assertInstanceOf("\Niko\Histogram\Histogram", $histogram);
    }
}
