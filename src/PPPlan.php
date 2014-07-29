<?php

namespace PPPlan;


class Ppplan {

    protected $colors;
    protected $lineColor = 'cyan';
    protected $listLineColor = 'white';

    public function __construct(Colors $colors)
    {
        $this->colors = $colors;
	}

    protected function color($string)
    {
        return $this->colors->getColoredString($string, $this->lineColor);
    }

    protected function listColor($string)
    {
        return $this->colors->getColoredString($string, $this->listLineColor);
    }

    public function theHead(Objective $objective)
    {
        $objective->title = readline($this->color("What do you want to do?\n\n"));
	}

    public function theQuestions(Objective $objective, array $answers)
    {
		$answers[0] = new Answer( $objective->title );
		while ( $this->thereAreUnestimated( $answers ) ) {
			foreach ( $answers as $answer ) {
				if ( $answer->hours > 0 or ! $answer->toEstimate ) {
					continue;
				}
				$this->askEstimationFor( $answer, $answers );
			}
		}

		return $answers;
	}

    protected function askEstimationFor(Answer $answer, array &$answers)
    {
        $line = $this->color("\nHow long will it take to $answer->title?\n(Either a fraction number or 0 for \"I do not know\")\n\n", 'cyan');
        $answer->hours = floatval(readline($line));
		if ( $answer->hours == 0 ) {
			$answer->toEstimate = false;
			$subAnswers         = $this->askDecomposeFor( $answer );
			foreach ( $subAnswers as $subAnswer ) {
				$answers[] = new Answer( $subAnswer );
			}
		} else {
			$answer->toEstimate = false;
		}
	}

    protected function askDecomposeFor(Answer $answer)
    {
        $subAnswers = explode(', ', readline($this->color("\nOk, what's needed to $answer->title?\n(Answer with a comma separated list)\n\n")));

		return $subAnswers;
	}

    protected function thereAreUnestimated(array $answers)
    {
		foreach ( $answers as $answer ) {
			if ( $answer->toEstimate ) {
				return true;
			}
		}
	}

    public function theResponse(Objective $objective, array $answers, $echo = true)
    {
        $list = $this->listColor(sprintf('Things to do to %s', $objective->title));
		$list .= "\n";
		$objective->totalHours = 0;
		foreach ( $answers as $answer ) {
			if ( $answer->hours == 0 ) {
				continue;
			}
			$tabs = "\n\t";
            $list .= $this->listColor(sprintf('%s- %s (est. %s hr%s)', $tabs, $answer->title, $answer->hours, Utils::getPluralSuffixFor($answer->hours)));
			$objective->totalHours += $answer->hours;
		}
		$list .= "\n\n";
        $list .= $this->listColor(sprintf('that\'s a total estimate of %s hour%s', $objective->totalHours, Utils::getPluralSuffixFor($objective->totalHours)));
		if ( $echo ) {
			echo "\n" . $list . "\n\n";
		}

		return $list;
	}

	public function theSavingOptions( $list ) {
        $answer = readline($this->color("Do you want to save the list to a file (y/n)? "));
		if ( ! preg_match( '/^(Y|y|yes|Yes)/', $answer ) ) {
			return;
		}
		$cwd      = getcwd();
        $filePath = $cwd . DIRECTORY_SEPARATOR . 'todo_' . time() . '.txt';
		@file_put_contents( $filePath, $list );
	}

}
