<?php

use PPPlan\Answer;

class AnswerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }
    public function affirmativeAnswerProvider(){
        return array(
            array('yes'),
            array('Yes'),
            array('y'),
            array('Y'),
            array('yep'),
            array('Yep')
        );
    }
    public function negativeAnswerProvider(){
        return array(
            array('no'),
            array('No'),
            array('n'),
            array('N'),
            array('nop'),
            array('Nay')
        );
    }
    /**
     * @test
     * it should validate affirmative answers using the is yes method
     * @dataProvider affirmativeAnswerProvider
     */
    public function it_should_validate_affirmative_answers_using_the_is_yes_method($yes)
    {
        $this->assertTrue(Answer::isYes($yes));
    }

    /**
     * @test
     * it should validate negative answers
     * @dataProvider negativeAnswerProvider
     */
    public function it_should_validate_negative_answers($no)
    {
        $this->assertTrue(Answer::isNo($no));
    }

    /**
     * @test
     * it should return false asserting is yes for negative answers
     * @dataProvider negativeAnswerProvider
     */
    public function it_should_return_false_asserting_is_yes_for_negative_answers($no)
    {
        $this->assertFalse(Answer::isYes($no));
    }

    /**
     * @test
     * it should return false asserting is no for positive answers
     * @dataProvider affirmativeAnswerProvider
     */
    public function it_should_return_false_asserting_is_no_for_positive_answers($yes)
    {
        $this->assertFalse(Answer::isNo($yes));
    }
}