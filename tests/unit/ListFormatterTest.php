<?php

use PPPlan\ListFormatter;

class ListFormatterTest extends \PHPUnit_Framework_TestCase
{
    protected $sut;
    protected function setUp()
    {
        $this->sut = new ListFormatter();
    }

    protected function tearDown()
    {
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $this->assertInstanceOf('\PPPLan\ListFormatter', new ListFormatter());
    }

    /**
     * @test
     * it should format a line to a format
     */
    public function it_should_format_a_line_to_a_format()
    {
        $task = new \PPPlan\Task();
    }
}