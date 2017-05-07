<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('convertToResponseArray')) {

    /**
     * @param array $objArr with __sleep() func
     *
     * @return array $return
     */
    function convertToResponseArray($objArr)
    {
        $return = [];
        foreach ($objArr as $obj) {
            array_push($return, getAllClassAttributes($obj));
        }

        return $return;
    }


    /***
     * Returns all attributes of given class
     *
     * @param $obj object with __sleep() func
     * @return array
     */
    function getAllClassAttributes($obj)
    {
        $allClassAttributes = [];

        foreach ($obj->__sleep() as $attr) {

            $attr = toCamelCase($attr);
//                This removes magic methods like __isInitialized__ from being called
            $method = 'get' . $attr;
            if (method_exists($obj, $method)) {
                $allClassAttributes[$attr] = call_user_func(array($obj, $method));
            }
        }

        return $allClassAttributes;
    }


    /***
     * Converts foo_barr to fooBar
     *
     * @param $string
     * @return mixed
     */
    function toCamelCase($string)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        $str[0] = strtolower($str[0]);

        return $str;
    }
}