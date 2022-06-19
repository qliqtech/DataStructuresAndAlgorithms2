<?php

namespace App\Helper;


/**
 * Helper class to handle data formats and conversions
 *
 */
class CharacterFormatHelper
{

    /**
     *convert csv file content to array
     *
     * @param string $filename
     * * @param $delimiter $filename
     * @return ?array
     */

    public static function csvToArray(string $filename = '',string $delimiter = ','):?array
    {

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }




}
