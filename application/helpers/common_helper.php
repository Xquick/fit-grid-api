<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('convertToResponseArray')) {

    /**
     * @param $obj
     * @param $attrArray
     */
    function convertToResponseArray($obj, $attrArray)
    {
        $returnArr = [];
        foreach ($attrArray as $attr) {
            $returnArr[$attr] = call_user_func(array($obj, 'get' . $attr));
        }

        return $returnArr;
    }
}