# PPPlan

A PHP script to help planning and estimating projects.

## Using it
It's a CLI script that can be cloned and run like
    
    git clone https://github.com/lucatume/ppplan.git
    php ppplan.php

At the end of the questions the script will allow you to save the answers to a <code>txt</code> file.  
If you want to later review the list of answers and break them down even further then use the <code>review</code> option provided by the script like

    php ppplan.php review some_list.txt

To be able to launch the script from the command line, once the file has been downloaded browse to the folder containing the script and type

    cp ./ppplan.php /usr/bin/ppplan

<code>sudo</code> authentication might be requested.

## Changelog
0.3.0 - added the possibility to review previously created lists
0.2.0 - colored the output and refactored the code  
0.1.0 - first working version
