<?php

use PPPlan\HourReader;

class HourReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $sut;
    protected function setUp()
    {
        $this->sut = new HourReader();
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
        $this->assertInstanceOf('\PPPlan\HourReader', new HourReader());
    }

    public function minutesProvider(){
        return array(
            array('1m', round(1/60, 2) ),
            array('1min', round(1/60, 2) ),
            array('1 minute', round(1/60, 2) ),
            array('1 mins', round(1/60, 2) ),
            array('1 m', round(1/60, 2) ),
            array('1 min', round(1/60, 2) ),
            array('1minute', round(1/60, 2) ),
            array('30.5m', round(30.5/60, 2))
        );
    }
    public function hoursProvider(){
        return array(
            array('1h', 1),
            array('1 h', 1),
            array('1hr', 1),
            array('1 hr', 1),
            array('1hour', 1),
            array('1 hour', 1),
            array('1hrs', 1),
            array('1 hrs', 1),
            array('4.5 hrs', 4.5)
        );
    }
    public function daysProvider(){
        return array(
            array('1d', 24),
            array('1 d', 24),
            array('1day', 24),
            array('1 day', 24),
            array('1days', 24),
            array('1 days', 24),
            array('2.4 days', 57.6)
        );
    }
    public function pomodorosProvider(){
        return array(
            array('1p', round(25/60, 2)),
            array('1 p', round(25/60, 2)),
            array('1pomodoro', round(25/60, 2)),
            array('1 pomodoro', round(25/60, 2)),
            array('1pomos', round(25/60, 2)),
            array('1 pomos', round(25/60, 2)),
            array('1pomodoros', round(25/60, 2)),
            array('1 pomodoros', round(25/60, 2)),
            array('4.5p', round(4.5*25/60, 2))
        );
    }
    /**
     * @test
     * it should read minutes properly
     * @dataProvider minutesProvider
     */
    public function it_should_read_minutes_properly($answer,$hours)
    {
        $this->assertEquals($hours, $this->sut->getHoursFrom($answer));
    }

    /**
     * @test
     * it should read hours properly
     * @dataProvider hoursProvider
     */
    public function it_should_read_hours_properly($answer, $hours)
    {
        $this->assertEquals($hours, $this->sut->getHoursFrom($answer));
    }

    /**
     * @test
     * it should read non specified time base as hours
     */
    public function it_should_read_non_specified_time_base_as_hours()
    {
        $this->assertEquals(3, $this->sut->getHoursFrom('3'));
    }

    /**
     * @test
     * it should read days properly
     * @dataProvider daysProvider
     */
    public function it_should_read_days_properly($answer, $hours)
    {
        $this->assertEquals($hours, $this->sut->getHoursFrom($answer));
    }

    /**
     * @test
     * it should properly read pomodoro values
     * @dataProvider pomodorosProvider
     */
    public function it_should_properly_read_pomodoro_values($answer, $hours)
    {
        $this->assertEquals($hours, $this->sut->getHoursFrom($answer));
    }

    /**
     * @test
     * it should allow setting the pomodoro duration
     */
    public function it_should_allow_setting_the_pomodoro_duration()
    {
        $this->sut->setPomodoroDuration(50);
        $this->assertEquals(round(50/60,2), $this->sut->getHoursFrom('1p'));
        $this->assertEquals(round(4*50/60,2), $this->sut->getHoursFrom('4p'));
    }

    /**
     * @test
     * it should allow setting the day duration
     */
    public function it_should_allow_setting_the_day_duration()
    {
        // 'day' is 'working 'day'
        $this->sut->setDayDuration(10);
        $this->assertEquals(10, $this->sut->getHoursFrom('1d'));
        $this->assertEquals(40, $this->sut->getHoursFrom('4d'));
    }
}