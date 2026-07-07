<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * Options Parser Interface.
 */
interface OptionsParserInterface
{
    /**
     * Parse input and extract all options and their values, this includes
     * all of the following formats (full & shortcuts):
     *
     * -i
     * --interactive
     * --connect xyz
     * -c=xyz
     * -cxyz
     * -ic
     *
     * Also, handle quotations if any exists
     *
     * @param string $argv
     * @return array
     */
    public function parse($argv);
}
