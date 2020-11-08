<?php

namespace Niko\Interfaces;

/**
 * A interface for a classes supporting histogram reports.
 */
interface HistogramInterface
{
    /**
     * Get the sequence.
     *
     * @return array with the serie.
     */
    public function getHistogramSequence();



    /**
     * Get min value for the histogram.
     *
     * @return int with the min value.
     */
    public function getHistogramMin();



    /**
     * Get max value for the histogram.
     *
     * @return int with the max value.
     */
    public function getHistogramMax();
}
