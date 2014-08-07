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
    /**
     * @test
     * it should validate affirmative answers using the is yes method
     * @dataProvider affirmativeAnswerProvider
     */
    public function it_should_validate_affirmative_answers_using_the_is_yes_method($yes)
    {
        $this->assertTrue(Answer::isYes($yes));
    }

}