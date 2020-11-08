<?php

namespace Niko\Histogram;

use Niko\Interfaces\HistogramInterface;

/**
* Generating histogram data.
*/
class Histogram
{
    /**
    * @var array $sequence  The numbers stored in sequence.
    * @var int   $min    The lowest possible number.
    * @var int   $max    The highest possible number.
    */
    private $sequence = [];
    private $min;
    private $max;

    /**
    * Get the sequence.
    *
    * @return array with the sequence.
    */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
    * Inject the object to use as base for the histogram data.
    *
    * @param HistogramInterface $object The object holding the serie.
    *
    * @return void.
    */
    public function injectData(HistogramInterface $object)
    {
        $this->sequence = $object->getHistogramSequence();
        $this->min   = $object->getHistogramMin();
        $this->max   = $object->getHistogramMax();
    }

    /**
    * Print out the histogram, default is to print out only the numbers in the sequence.
    * @return array Returns the sorted histogram.
    */
    public function getAsText()
    {
        $histogram = array_count_values($this->sequence);
        ksort($histogram, SORT_NUMERIC);

        foreach ($histogram as $key => $value) {
            echo $key . ": ";
            for ($index=0; $index < $value; $index++) {
                echo "*";
            }
            echo "\n";
        }
    //    Used for testing
    //    return $histogram;
    }

    /**
    * Print out the histogram, the amount of rows depends on
    * the instance variables $min and $max.
    * @return array Returns the sorted histogram.
    */
    public function getAsTextLimited()
    {
        $histogram = array_count_values($this->sequence);
        ksort($histogram, SORT_NUMERIC);

        for ($i = $this->min; $i <= $this->max; $i++) {
            echo $i . ": ";

            if (isset($histogram[$i])) {
                for ($j=0; $j < $histogram[$i]; $j++) {
                    echo "*";
                }
            }

            echo "\n";
        }
    //    Used for testing
    //    return $histogram;
    }
}
