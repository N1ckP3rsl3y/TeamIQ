# TeamIQ

**Version:** v0.1.0 (alpha-demo)

# RedPen
## Overview
This project was proposed and is sponsored by Dr. Erika Konrad at Northern Arizona University, and is a professor of graduate courses in grammar and technical-writing.

Our project -- dubbed the name *RedPen* -- is an interactive quiz editing web application. The application is meant to take after the features from the New York Times' [*Copy Edit This!*](https://www.nytimes.com/interactive/2016/11/11/insider/copy-edit-this-quiz.html).

The following points are some of the main problems with *Copy Edit This!* that were noticed by Dr. Konrad:
* It has a set group of quizzes to choose from for an instructor assigning them.
* An instructor does not have any view in which they can create a class, and view the results of their students.
* The cost of the application is not desired by our sponsor nor her students.

So, our goal is to make a custom web application to address these problems and the professor, for her classes, can create custom quizzes and view the results of her students throughout the semesters.

For more information, please see [Team IQ's website](https://www.ceias.nau.edu/capstone/projects/CS/2024/IQ_F23/project.html).

See the current state of the [project](https://ac.nau.edu/redpen/index.html).

## Built With
* NAU ITS MySQL/Apache server
* HTML/CSS/JavaScript
* SQL

## Authors
* **Logan Samstag** - Team Leader/Back-End Developer
* **Nicholas Persley** - Back-End Developer/Release Manager
* **Kristiana Kirk** - Front-End Developer
* **Robin Pace** - Back-End Developer
* **Elian Zamora** - Front-End Developer

## Versioning
Versioning will follow the semantic versioning convention outlined in [SemVar](http://semver.org/).

## Testing
* Unit Tests
    - A basic test was conducted using PHPUnit to test user authentication on the NAU CEIAS server

* Integration Testing
    - Bottom-up approach
    - Checked that the PHP functions connected to the database operated properly and recieved the right values
    - Tested PHP functions and JavaScript functions with dummy variables and sometimes a temporary database
    - Tested any functions a level above that that call the php and javascript functions

* User Testing
    - As the program production started to wind down, the following methods took place to have user experience testing:
        1. Our sponsor, Dr. Konrad gave out the link to her students and had them try to break the website
        2. Some members of the team also gathered a couple people each of their own accord to have students try the website and attempt to break it

    - A special thank you to all that took time out of their day to help!
