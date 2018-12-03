<?php

use PHPUnit\Framework\TestCase;

/**
 * tests demo
 *
 * @author i@flc.io
 */
class QueryTests extends TestCase
{
    public function testCount()
    {
        $tests = [1];

        $this->assertCount(0, $tests);
    }
}