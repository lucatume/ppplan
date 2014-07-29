<?php

namespace PPPlan;


class Ppplan {

    protected $colors;
    protected $lineColor = 'green';
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
        if ($objective->title != '') {
            $line = sprintf('Reviewing the things to do to %s', $objective->title);
            echo $this->color($line);
        } else {
            $objective->title = readline($this->color("What do you want to do?\n\n"));
        }
	}

    public function theQuestions(Objective $objective, array $answers)
    {
        $answers[0] = new Answer($objective->title, $objective->totalHours, false);
        while ($this->thereAreUnestimated($answers)) {
			foreach ( $answers as $answer ) {
                if (!$answer->toEstimate and $answer->hours == 0) {
					continue;
				}
				$this->askEstimationFor( $answer, $answers );
			}
        }

		return $answers;
	}

    protected function askEstimationFor(Answer $answer, array &$answers)
    {
        if ($answer->hours > 0) {
            echo $this->color("\nPrevious estimate to $answer->title is $answer->hours hours.", 'cyan');
        }
        if ($answer->toEstimate) {
            $line = $this->color("\nHow long will it take to $answer->title?\n(Either a fraction number or 0 for \"I do not know\")\n\n", 'cyan');
            $answer->hours = floatval(readline($line));
        }
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
        $fileList = sprintf('Things to do to %s', $objective->title);
        $fileList .= "\n";
		$objective->totalHours = 0;
        // remove the objective from the answers
        unset ($answers[0]);
        $answers = array_values($answers);
		foreach ( $answers as $answer ) {
			if ( $answer->hours == 0 ) {
				continue;
			}
			$tabs = "\n\t";
            $fileList .= sprintf('%s- %s (est. %s hr%s)', $tabs, $answer->title, $answer->hours, Utils::getPluralSuffixFor($answer->hours));
			$objective->totalHours += $answer->hours;
		}
        $fileList .= "\n\n";
        $fileList .= sprintf('that\'s a total estimate of %s hour%s', $objective->totalHours, Utils::getPluralSuffixFor($objective->totalHours));
		if ( $echo ) {
            echo "\n" . $this->listColor($fileList) . "\n\n";
		}

        return $fileList;
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
