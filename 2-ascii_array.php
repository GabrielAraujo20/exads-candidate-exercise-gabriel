<?php
/**
 * Generate a random array of ASCII characters between ","(44) and "|"(124) and remove a random char
 * @return array
 */
function generateRandomArray(): array
{
    $aschiiArr = array();
    $aschiiValuesArr = array();
    for($i = 44; $i <= 124; $i++) {
        do {   
            $n = rand(44,124);
        
        } while(in_array($n, $aschiiValuesArr));
        $aschiiValuesArr[] = $n;
        $aschiiArr[] = chr($n);
    }
    //remove random char
    unset($aschiiArr[rand(0, count($aschiiArr) - 1)]);
    return $aschiiArr;
}

/**
 * Find the missing char in the ASCII array
 * @return string
 */
function findMissingChar(array $asciiArr): string
{
    sort($asciiArr);

    $expectedValue = 44;
    $missingChar = '|';
    for($j=0; $j < count($asciiArr); $j++) {
        if(ord($asciiArr[$j]) !== $expectedValue) {
            $missingChar = chr($expectedValue);
            break;
        }
            $expectedValue++;
    }
    return $missingChar;
}

$asciiArr = generateRandomArray();

echo "ASCII array: ". implode(',', $asciiArr). "\n";

$missingChar = findMissingChar($asciiArr);


echo "missing char: ". $missingChar. "\n";