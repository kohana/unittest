# Troubleshooting

## I get the error "Class Kohana_Tests could not be found" when testing from the CLI

You need to running PHPUnit >= 3.4, there is a bug in 3.3 which causes this.

## I'm getting an error saying "Cannot redeclare class {class}".  I've overriden the class in my app

If you override a class which is included automatically during the request then you need to tell PHPUnit to
ignore the base extension (i.e. Base extension for Kohana_HTML is HTML in kohana/classes/html.php)

To do this you need to add