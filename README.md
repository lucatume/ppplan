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

### Review
If you want to later review the list of answers and break them down even further then use the <code>review</code> option provided by the script like

    php ppplan.php review some_list.txt

## Changelog
0.4.0 - refactoring and added the clear mode
0.3.0 - added the possibility to review previously created lists
0.2.0 - colored the output and refactored the code  
0.1.0 - first working version
