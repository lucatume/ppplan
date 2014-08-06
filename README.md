# PPPlan

A PHP script to help planning and estimating projects.

The script was born out of my need for a tool to streamline my task planning and estimation workflow. The output of the script will be a simple <code>txt</code> file that can then be imported and modified anywhere or kept in a project folder under version control.  
The script is **not** meant to be a task manager of sorts and will output a flat list of tasks **on purpose**; the idea behind it is that good planning and estimation should break a large project into **independent, finite and smaller sub tasks** that can be performed in a parallel workflow.

## Using it
It's a CLI script that can be cloned and run like
    
    git clone https://github.com/lucatume/ppplan.git
    php ppplan.php


## Options

### Clear mode
For a more focused interaction with the script use the <code>--clear</code> option to have a clear screen for each question and focus on the question at hand, use it like

    php ppplan.php --clear

### Day, week and pomodoro duration
PPPlan will understand natural language task durations like `1w` or `3p` to specify a task duration estimation and will allow specifying durations using options too; by defaults **a day is 24 hours long, a week is 7 days long and a pomodoro is 25 minutes long**.  
Using the `--dayDuration`, `--weekDuration` and `--pomodoroDuration` options like

    ppplan --dayDuration 8 --weekDuration 5 --pomodoroDuration 50

I'm specifying that `1 day` means 8 hours, `1 week` means 5 days and `1 pomodoro` means 50 minutes.  

### Output unit
By default estimate values will be expressed in hours but the script allows overriding that behaviour using the `--outputUnit` option like

    ppplan --outputUnit [minute|hour|pomodoro|day|week]
## Persisting options
To allow for a more streamlined use of the script the command arguments that should be input on each script call can be saved to the `~/.ppplan` file to have them read and used on each `ppplan` call. Run time options will override the ones set in the file though. The file format is the same as the command line options hence, to persist the options about day, week and pomodoro duration, I could type something like
    
    echo "--dayDuration 8 --weekDuration 5 --pomodoroDuration 50" >> ~/.ppplan
    ppplan
    
### Review
If you want to later review the list of answers and break them down even further then use the <code>review</code> option provided by the script like

    php ppplan.php review some_list.txt

To be able to launch the script from the command line, once the file has been downloaded browse to the folder containing the script and type

    cp ./ppplan.php /usr/bin/ppplan

<code>sudo</code> authentication might be requested.

## Changelog
0.5.0 - added the possibility to enter durations in minutes, hours,days, weeks and pomodoros and persist options
0.4.0 - refactoring and added the clear mode
0.3.0 - added the possibility to review previously created lists
0.2.0 - colored the output and refactored the code  
0.1.0 - first working version
