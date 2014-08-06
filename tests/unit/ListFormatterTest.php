<?php
use PPPlan\ListFormatter;
use PPPlan\Objective;
use PPPlan\Task;
use PPPlan\Unit;

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
        $this->assertEquals("\t- do some task (est. 4 hours)", $line);
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
        $this->assertEquals("\t- do some task (est. 1 hour)", $line);
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
        $this->assertEquals("Do something: @est(5)\nestimates in hours", $line);
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
        $this->assertEquals("Things to do to do something:\n", $line);
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
        $this->assertEquals("\t- do some task (est. 1.3 hours)", $line);
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
        $this->assertEquals("\n\nThat's a total estimate of 5 hours.", $line);
    }
    
    /**
     * @test
     * it should format a whole list to taskpaper format
     */
    public function it_should_format_a_whole_list_to_taskpaper_format()
    {
        $objective = new Objective('do something', 2.8);
        $task1 = new Task('do task one', 1.5, false);
        $task2 = new Task('do task two', 1.3, false);
        $sut = new ListFormatter('taskpaper');
        $actual = $sut->formatList($objective, array(
            $task1,
            $task2
        ));
        $expected = "Do something: @est(2.8)\nestimates in hours\n\t- do task one @est(1.5)\n\t- do task two @est(1.3)";
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * it should format a list to plain text format
     */
    public function it_should_format_a_list_to_plain_text_format()
    {
        $objective = new Objective('do something', 2.8);
        $task1 = new Task('do task one', 1.5, false);
        $task2 = new Task('do task two', 1.3, false);
        $sut = new ListFormatter('txt');
        $actual = $sut->formatList($objective, array(
            $task1,
            $task2
        ));
        $expected = "Things to do to do something:\n\n\t- do task one (est. 1.5 hours)\n\t- do task two (est. 1.3 hours)\n\nThat's a total estimate of 2.8 hours.";
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * it should allow overriding the format setting the  format property
     */
    public function it_should_allow_overriding_the_format_setting_the_format_property()
    {
        $objective = new Objective('do something', 2.8);
        $task1 = new Task('do task one', 1.5, false);
        $task2 = new Task('do task two', 1.3, false);
        $sut = new ListFormatter('txt');
        $sut->setFormat('taskpaper');
        $actual = $sut->formatList($objective, array(
            $task1,
            $task2
        ));
        $expected = "Do something: @est(2.8)\nestimates in hours\n\t- do task one @est(1.5)\n\t- do task two @est(1.3)";
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * it should allow setting the output to different bases
     */
    public function it_should_allow_setting_the_output_to_different_bases()
    {
        $objective = new Objective('do something', 3);
        $task1 = new Task('do task one', 2, false);
        $task2 = new Task('do task two', 1, false);
        $sut = new ListFormatter('txt');
        // a pomodoro is 30 mins on 1 hour => 0.5
        $unit = new Unit(30 / 60, 'pomodoro');
        // set the base to one pomodoro + pause
        $sut->setUnit($unit);
        $actual = $sut->formatList($objective, array(
            $task1,
            $task2
        ));
        $expected = "Things to do to do something:\n\n\t- do task one (est. 4 pomodoros)\n\t- do task two (est. 2 pomodoros)\n\nThat's a total estimate of 6 pomodoros.";
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * it should properly output taskpaper format head reporting the estimate unit
     */
    public function it_should_properly_output_taskpaper_format_head_reporting_the_estimate_unit()
    {
        $objective = new Objective('do something', 3);
        $task1 = new Task('do task one', 2, false);
        $task2 = new Task('do task two', 1, false);
        $sut = new ListFormatter('taskpaper');
        // a pomodoro is 30 mins on 1 hour => 0.5
        $unit = new Unit(30 / 60, 'pomodoro');
        // set the base to one pomodoro + pause
        $sut->setUnit($unit);
        $actual = $sut->formatList($objective, array(
            $task1,
            $task2
        ));
        $expected = "Do something: @est(6)\nestimates in pomodoros\n\t- do task one @est(4)\n\t- do task two @est(2)";
        $this->assertEquals($expected, $actual);
    }
}
