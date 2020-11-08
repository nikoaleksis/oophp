<?php

namespace Niko\Traits;

/**
* A trait implementing HistogramInterface.
*/
trait HistogramTrait
{
    /**
    * @var array $sequence  The numbers stored in sequence.
    */
    private $sequence = [];



    /**
    * Get the sequence.
    *
    * @return array with the serie.
    */
    public function getHistogramSequence()
    {
        return $this->sequence;
    }

    /**
    * Get min value for the histogram.
    *
    * @return int with the min value.
    */
    public function getHistogramMin()
    {
        return 1;
    }



    /**
    * Get max value for the histogram.
    *
    * @return int with the max value.
    */
    public function getHistogramMax()
    {
        return max($this->sequence);
    }
}
