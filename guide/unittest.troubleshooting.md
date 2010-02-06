# Troubleshooting

## I get the error "Class Kohana_Tests could not be found" when testing from the CLI

You need to running PHPUnit >= 3.4, there is a bug in 3.3 which causes this.

## Some of my classes aren't getting whitelisted for code coverage even though their module is

Only the "highest" files in the cascading filesystem are whitelisted for code coverage.

To test your module's file, remove the higher file from the cascading filesystem