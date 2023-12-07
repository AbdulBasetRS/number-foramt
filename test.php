<?php

use Abdulbaset\NumberFormat\Helpers\NumberToWordsHelper;

$numbers = new NumberToWordsHelper();
$numbers->setNumbers(100);
echo $numbers->getNumbers();
