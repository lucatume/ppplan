<?php

use PPPlan\OptionReader;

class OptionReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $sut = null;
    protected function setUp()
    {
        $this->sut = new OptionReader();
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
        $this->assertInstanceOf('PPPlan\OptionReader', new OptionReader());
    }

    public function optionProvider(){
        return array(
            array(array('ppplan','--key', 'value'), array('key' => 'value'))
        );
    }

    /**
     * @test
     * it should return an empty array if no options are specified
     */
    public function it_should_return_an_empty_array_if_no_options_are_specified()
    {
        $this->assertEquals(array(), get_object_vars($this->sut->getOptionsFrom(array('ppplan'))));
    }
    /**
     * @test
     * it should read options in the format double dash option name space option value
     * @dataProvider optionProvider
     */
    public function it_should_read_options_in_the_format_double_dash_option_name_space_option_value($args, $expectedKeyValuePairs)
    {
        $this->assertEquals($expectedKeyValuePairs , get_object_vars($this->sut->getOptionsFrom($args)));
    }

    /**
     * @test
     * it should read options with no value
     */
    public function it_should_read_options_with_no_value()
    {
        $this->assertEquals(array('some' => true), get_object_vars($this->sut->getOptionsFrom(array('ppplan', '--some'))));
    }

    /**
     * @test
     * it should read args with more than one option and corresponding values
     */
    public function it_should_read_args_with_more_than_one_option_and_corresponding_values()
    {
        $in = array('ppplan', '--some', 'option', '--another', 'option', '--more', 'options');
        $out = array('some' => 'option', 'another' => 'option', 'more' => 'options');
        $this->assertEquals($out, get_object_vars($this->sut->getOptionsFrom($in)));
    }

    /**
     * @test
     * it should read args with option values and without option values
     */
    public function it_should_read_args_with_option_values_and_without_option_values()
    {
        $in = array('ppplan', '--some', '--another', 'option', '--more');
        $out = array('some' => true, 'another' => 'option', 'more' => true);
        $this->assertEquals($out, get_object_vars($this->sut->getOptionsFrom($in)));
    }

    /**
     * @test
     * it should read args with no option values
     */
    public function it_should_read_args_with_no_option_values()
    {
        $in = array('ppplan', '--some', '--another', '--more');
        $out = array('some' => true, 'another' => true, 'more' => true);
        $this->assertEquals($out, get_object_vars($this->sut->getOptionsFrom($in)));
    }
}