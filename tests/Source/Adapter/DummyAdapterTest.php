<?php

use PHPUnit\Framework\TestCase;
use Dealweb\Integrator\Source\Adapter\DummyAdapter;

class DummyAdapterTest extends TestCase
{
    /** @test */
    public function it_returns_false()
    {
        $dummyAdapter = new DummyAdapter;

        $dummyAdapterReturn = $dummyAdapter->process([]);

        $this->assertFalse($dummyAdapterReturn);
    }
}
