<?php
use PPPlan\ListFormatter;
use PPPlan\Task;
use PPPlan\Objective;

class ListFormatterTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
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
     * it should format a line to taskpaper format
     */
    public function it_should_format_a_line_to_taskpaper_format()
    {
        $task = new Task('do some task', 4, false);
        $sut = new ListFormatter('taskpaper');
        $line = $sut->formatLine($task);
        $this->assertEquals("\t- do some task @est(4)", $line);
    }
    
    /**
     * @test
     * it should format a line to plain text
     */
    public function it_should_format_a_line_to_plain_text()
    {
        $task = new Task('do some task', 4, false);
        $sut = new ListFormatter('txt');
        $line = $sut->formatLine($task);
        $this->assertEquals("\t- do some task (est. 4 hrs)", $line);
    }
    
    /**
     * @test
     * it should use singular when estimate is an hour or less in plain text format
     */
    public function it_should_use_singular_when_estimate_is_an_hour_or_less_in_plain_text_format()
    {
        $task = new Task('do some task', 1, false);
        $sut = new ListFormatter('txt');
        $line = $sut->formatLine($task);
        $this->assertEquals("\t- do some task (est. 1 hr)", $line);
    }
        /**
         * @test
         * it should format the head of the list to taskpaper format
         */
        public function it_should_format_the_head_of_the_list_to_taskpaper_format()
        {
            $objective = new Objective('do something', 5);
            $sut = new ListFormatter('taskpaper');
            $line = $sut->formatHead($objective);
            $this->assertEquals("Do something: @est(5)\n\n", $line);
        }
        /**
         * @test
         * it should format the head of the list to plain text format
         */
        public function it_should_format_the_head_of_the_list_to_plain_text_format()
        {
        $objective = new Objective('do something', 5);
        $sut = new ListFormatter('txt');
        $line = $sut->formatHead($objective);
        $this->assertEquals("Things to do to do something:\n\n", $line);
        }
        /**
         * @test
         * it should output floating hours when formatting lines
         */
        public function it_should_output_floating_hours_when_formatting_lines()
        {
            $task = new Task('do some task', 1.3, false);
            $sut = new ListFormatter('txt');
            $line = $sut->formatLine($task);
            $this->assertEquals("\t- do some task (est. 1.3 hrs)", $line);
        }
        /**
         * @test
         * it should format the foot of the list to taskpaper format
         */
        public function it_should_format_the_foot_of_the_list_to_taskpaper_format()
        {
        $objective = new Objective('do something', 5);
        $sut = new ListFormatter('taskpaper');
        $line = $sut->formatFoot($objective);
        $this->assertEquals("", $line);
        }
        /**
         * @test
         * it should format the foot of the list to plain text format
         */
        public function it_should_format_the_foot_of_the_list_to_plain_text_format()
        {
        $objective = new Objective('do something', 5);
        $sut = new ListFormatter('txt');
        $line = $sut->formatFoot($objective);
        $this->assertEquals("\nThat's a total estimate of 5 hours.", $line);
        }
}
