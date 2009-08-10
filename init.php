<?php

if(class_exists('PHPUnit_Util_Filter'))
{
    restore_exception_handler();
    restore_error_handler();
    define('SUPPRESS_REQUEST', TRUE);
}