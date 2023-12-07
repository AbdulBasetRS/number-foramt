<?php

namespace Abdulbaset\NumberFormat\Helpers;

class NumberToWordsHelper
{
    private $number;

    public function setNumbers($numbers)
    {
        $this->number = $numbers;
    }

    public function getNumbers()
    {
        return $this->number;
    }

    public function getWords()
    {
        $ones = [
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen'
        ];

        $tens = [
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
            6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
        ];

        $scales = [
            '', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion',
            'Quintillion', 'Sextillion', 'Septillion', 'Octillion', 'Nonillion', 'Decillion'
        ];

        if ($this->getNumbers() == 0) {
            return $ones[0];
        }

        $parts = [];
        $scaleCount = 0;

        while ($this->getNumbers() > 0) {
            $chunk = $this->getNumbers() % 1000;
            if ($chunk != 0) {
                $chunkStr = '';
                $units = (int) ($chunk / 100);
                $remainder = $chunk % 100;

                if ($units > 0) {
                    $chunkStr .= $ones[$units] . ' Hundred';
                }

                if ($remainder > 0) {
                    if ($remainder < 20) {
                        $chunkStr .= ($units > 0 ? ' ' : '') . $ones[$remainder];
                    } else {
                        $tensDigit = (int) ($remainder / 10);
                        $onesDigit = $remainder % 10;
                        $chunkStr .= ($units > 0 ? ' ' : '') . $tens[$tensDigit];
                        if ($onesDigit > 0) {
                            $chunkStr .= '-' . $ones[$onesDigit];
                        }
                    }
                }

                $chunkStr .= ' ' . $scales[$scaleCount];
                $parts[] = $chunkStr;
            }

            $number = (int) ($this->getNumbers() / 1000);
            $scaleCount++;
        }

        $parts = array_reverse($parts);
        return implode(', ', $parts);
    }

    public function convertPriceToWords($currency = null)
    {
        if ($currency == 'USD') {
            $price_type = 'dollars';
            $decimal_type = 'cents';
        } elseif ($currency == 'EGP') {
            $price_type = 'جنية';
            $decimal_type = 'قرشاً';
        } else {
            $price_type = '';
            $decimal_type = '';
        }

        $integerPart = (int) $this->getNumbers();
        $decimalPart = (int) round(($this->getNumbers() - $integerPart) * 100);

        $integerPartWords = $this->getWords($integerPart);
        $decimalPartWords = $this->getWords($decimalPart);

        $result = $integerPartWords . ' ' . $price_type;

        if ($decimalPart > 0) {
            $result .= ' and ' . $decimalPartWords . ' ' . $decimal_type;
        }

        return $result;
    }
}