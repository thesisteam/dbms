<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Class for all data abstraction and manipulation layer of the application
 *
 * @author Allen
 */
final class DATA {
    
    /**
     * Extracts certain value from a specified $_POST data, otherwise, returns null
     * @param Array(Assoc) $POST_DATA Source $_POST data
     * @param String $datakey The data key of the value
     * @param Boolean $is_trimspaces Boolean value whether left-right trailing spaces should be trimmed out
     * @param Boolean $is_striphtml Boolean value whether HTML tags should be stripped out
     * @param Boolean|null $is_tolower True will make LowerCase, otherwise, UpperCase, NULL makes it do nothing
     * @return Mixed|null
     */
    public static function __ExtractPost($POST_DATA, $datakey, $is_trimspaces=false, $is_striphtml=false, $is_tolower=null){
        if (!array_key_exists($datakey, $POST_DATA)) {
            return null;
        }
        $data = $POST_DATA[$datakey];
        if ($is_striphtml) {
            $data = strip_tags($data);
        }
        if ($is_trimspaces) {
            $data = trim($data);
        }
        if (!is_null($is_tolower)) {
            $data = $is_tolower ? strtolower($data) : strtoupper($data);
        }
        return $data;
    }
    
    /**
     * Gets data from specified POST data key, otherwise, returns null
     * @param String $datakey The key of data to be fetched from $_POST
     * @param Boolean $is_trimspaces Boolean value whether left-right trailing spaces should be trimmed out
     * @param Boolean $is_striphtml Boolean value whether HTML tags should be stripped out
     * @param Boolean|null $is_tolower True will make LowerCase, otherwise, UpperCase, NULL makes it do nothing
     * @return Mixed|null
     */
    public static function __GetPOST($datakey, $is_trimspaces=false, $is_striphtml=false, $is_tolower=null) {
        if (!array_key_exists($datakey, $_POST)) {
            return null;
        }
        $data = $_POST[$datakey];
        if ($is_striphtml) {
            $data = strip_tags($data);
        }
        if ($is_trimspaces) {
            $data = trim($data);
        }
        if (!is_null($is_tolower)) {
            $data = $is_tolower ? strtolower($data) : strtoupper($data);
        }
        return $data;
    }
    
    /**
     * Checks if $_POST has content during the load of this page
     * @param string $datakey (Optional) The key of the post data to be extracted
     * @return boolean
     */
    public static function __HasPostData($datakey = null) {
        if (is_null($datakey)) {
            return count($_POST) > 0;
        } else {
            return array_key_exists($datakey, $_POST);
        }
    }
    
    /**
     * Reformats a date %m/%d/%Y into another format
     * @param type $str_date The date string in format %m/%d/%Y
     * @param type $formatmask New date format: e.g. %d-%m-%Y
     * @return The new date with respect to '$formatmask'
     */
    public static function __ReformatDate($str_date, $formatmask) {
        $month = substr($str_date, 0, 2);
        $day = substr($str_date, 3, 2);
        $year = substr($str_date, 5, 2);
        $date = $formatmask;
        
        $date = str_replace('%m', $month, $date);
        $date = str_replace('%d', $day, $date);
        $date = str_replace('%Y', $year, $date);
        return $date;
    }
    
}
