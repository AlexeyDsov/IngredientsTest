<?php
//work -> onPHP
$pattern = '(\n\t+)->';
$change = '->$1';

//work -> onPHP
$pattern = '([ \t](?:class|function).+?)[ ]?\{\n(\t*)\t';
$change = '$1\n$2{\n$2\t';


?>