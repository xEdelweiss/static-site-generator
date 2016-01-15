<?php

require 'vendor/autoload.php';

$generator = new \xEdelweiss\SSG\Generator();
echo $generator->markdownToText('#hello world!');