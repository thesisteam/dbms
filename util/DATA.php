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
    
    private static $SESS_DATALOCK_TIME = 'emit_kcolatad';
    private static $SESS_DATALOCK_KEY = '_yek_kcolatad';
    
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
     * Generate a random hash string
     * @param int $length (Optional) The length of the hash, max of 32
     * @param int $timestamp (Optional) If SUPPLIED, random hash will be generated
     *      based on this value
     * @return String
     */
    public static function __GenerateRandomhash($length=32, $timestamp=null) {
        $timehash = (is_null($timestamp) ? time() : $timestamp);
        return substr(md5($timehash), 0, $length);
    }
    
    /**
     * Gets data from specified GET data key, otherwise, returns null
     * @param String $datakey The key of data to be fetched from $_GET
     * @param Boolean $is_trimspaces Boolean value whether left-right trailing spaces should be trimmed out
     * @param Boolean $is_striphtml Boolean value whether HTML tags should be stripped out
     * @param Boolean|null $is_tolower True will make LowerCase, otherwise, UpperCase, NULL makes it do nothing
     * @return Mixed|null
     */
    public static function __GetGET($datakey, $is_trimspaces=false, $is_striphtml=false, $is_tolower=null) {
        if (!array_key_exists($datakey, $_GET)) {
            return null;
        }
        $data = $_GET[$datakey];
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
    
    /**
     * Returns a boolean value whether the GET passage gate is open or not
     * @return boolean Boolean value if the GET passage gate is open or not
     */
    public static function __IsPassageOpen() {
        if (!isset($_SESSION[self::$SESS_DATALOCK_KEY]) || !isset($_SESSION[self::$SESS_DATALOCK_TIME])) {
            return false;
        }
        $sessTime = $_SESSION[self::$SESS_DATALOCK_TIME];
        $sessHash = $_SESSION[self::$SESS_DATALOCK_KEY];
        return self::__GenerateRandomhash(32, $sessTime) == $sessHash;
    }
    
    /**
     * Close all intent passages<br>
     * This is useful when you want to invalidate all programmer-defined GET values
     */
    public static function closePassage($is_clearintents=true) {
        unset($_SESSION[self::$SESS_DATALOCK_TIME]);
        unset($_SESSION[self::$SESS_DATALOCK_KEY]);
        if ($is_clearintents && (count($_SESSION) > 0)) {
            $ctr = 0;
            do {
                if (strstr(key($_SESSION), "intent_")) {
                    unset($_SESSION[key($_SESSION)]);
                } next($_SESSION);
                $ctr++;
            } while($ctr < count($_SESSION));
            reset($_SESSION);
        }
    }
    
    /**
     * Open all GET method passages<br>
     * This is useful when you want to allow all programmer-defined GET values
     */
    public static function openPassage() {
        $_SESSION[self::$SESS_DATALOCK_TIME] = time();
        $_SESSION[self::$SESS_DATALOCK_KEY] = self::__GenerateRandomhash();
    }
    
    /**
     * Returns certain value from existing intent, otherwise, returns NULL
     * @param String $intentname The intent name
     * @return Mixed|null
     */
    public static function __GetIntent($intentname) {
        if (!array_key_exists("intent_" . $intentname, $_SESSION)) {
            return null;
        }
        return $_SESSION["intent_" . $intentname];
    }
    
    /**
     * Create a value for certain intent
     * @param String $intentname The intent name
     * @param Mixed $value The value of the intent
     */
    public static function CreateIntent($intentname, $value) {
        $_SESSION["intent_" . $intentname] = $value;
    }
    
    /**
     * Delete an entire intent
     * @param String $intentname Name of the intent to be deleted/disposed
     * @return boolean Boolean value if an existing intent was deleted,
     *      otherwise, no intent was deleted.
     */
    public static function DeleteIntent($intentname) {
        if (array_key_exists("intent_" . $intentname, $_SESSION)) {
            unset($_SESSION["intent_" . $intentname]);
            return true;
        }
        return false;
    }
    
}
