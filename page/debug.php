<?php

/**
 * This file is for debugging and app development purposes only.
 * Should not be used for front-end features.
 * Possible purposes are:
 * -- you want to test the functionality of the methods you created from another registered class.
 * -- or you may want to test the effective of such algorithms.
 * 
 */

# Begin coding below

$harhar = 'hello world';
$key = 'hello ka din haha';
$value = $harhar & $key;
echo $value;

$value = $key&$value;
echo ' decrypted(' . $value . ')';

?>