<?php namespace Tests\Sample;

use Framework\Sample\Sample;
use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase
{
	protected Sample $sample;

	public function setup() : void
	{
		$this->sample = new Sample();
	}

	public function testSample()
	{
		$this->assertEquals(
			'Framework\Sample\Sample::test',
			$this->sample->test()
		);
	}
}
