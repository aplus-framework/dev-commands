<?php namespace Tests\CLI\Commands;

use Framework\CLI\Commands\Sample;
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
			'Framework\CLI\Commands\Sample::test',
			$this->sample->test()
		);
	}
}
