# Testing workflows

Having unittests for your application is a nice idea, but unless you actually use them they're about as useful as a chocolate firegaurd.  There are quite a few ways of getting tests "into" your development process and this guide aims to cover a few of them.

## Testing through the webui

The web ui is a fairly temporary solution, aimed at helping developers get into unittesting and code coverage.  Eventually it's hoped that people migrate on to CI servers, but it's fine for calculating code coverage locally.

To access it goto

	http://example.com/unittest/

*Note:* Your site will need to be in the correct environment in order to use the webui.  See the config file for more details.  You may also need to use http://example.com/index.php/unittest/

## Integrating with IDEs

Modern IDEs have come a long way in the last couple of years and ones like netbeans have pretty decent PHP / PHPUnit support.

### Netbeans (6.8+)

*Note:* Netbeans runs under the assumption that you only have one tests folder per project.  
If you want to run tests across multiple modules it might be best creating a separate project for each module.

0. Install the unittest module

1. Open the project which you want to enable phpunit testing for.

2. Now open the project's properties dialouge and in the "Tests Dir" field enter the path to your module's (or application's) test directory.  
   In this case the only tests in this project are within the unittest module

3. Select the phpunit section from the left hand pane and in the area labelled bootstrap enter the path to your app's index.php file

You can also specify a custom test suite loader (enter the path to your tests.php file) and/or a custom configuration file (enter the path to your phpunit.xml file)

## Looping shell

I personally prefer to do all of my development in an advanced text editor such as vim/gedit/np++.

To test while I work I run tests in an infinte looping.  It's very easy to setup and only takes a few commands to setup.  
On nix you can run the following commands in the terminal:

	while(true) do clear; phpunit; sleep 8; done;

In my experience this gives you just enough time to see what's going wrong before the tests are rerun.  
It's also quite handy to store common phpunit settings (like path to the bootstrap) in a a phpunit xml file to reduce the amount that has to be written in order to start a loop.

## Continuous Integration (CI)

Continuous integration is a team based tool which enables developers to keep tabs on whether changes committed to a project break the application. If a commit causes a test to fail then the build is classed as "broken" and the CI server then alerts developers by email, RSS, IM or glowing (bears|lava lamps) to the fact that someone has broken the build and that all hell's broken lose.

The two more popular CI servers are [Hudson](https://hudson.dev.java.net/) and [phpUnderControl](http://www.phpundercontrol.org/about.html), both of which use [Phing](http://phing.info/) to run the build tasks for your application.
