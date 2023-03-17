<?php


function readcsv(string $csvFilePath): array|false
{
    $csv = array_map('str_getcsv', file($csvFilePath));
    for($i = 0; $i < count($csv); $i++)
    {
        for($j = 0; $j < count($csv[$i]); $j++)
        {
            $line[] = $csv[$i][$j];
        }
        
    }
    foreach($line as $l)
    {
        $datas[] = explode(';', $l);
    }

    return $datas;
}



