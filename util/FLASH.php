<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Class for Flash messages management
 *
 * @author Allen
 */
class FLASH {
    
    public static $FLASH_SESS_KEY = '_flash';           # Session key for flash contents
    public static $FLASH_DEDICATION_KEY = '_flash_page_dedication'; # Session key for FLASH page dedication
    public static $FLASH_SESS_TYPE = '_flash_type';     # Session key for flash type
    
    public static $Flashes = array();

    /**
     * The main function to be called when showing Flashes
     */
    public static function Initialize() {
        if (self::_hasFlashes()) {
            echo '<div class="container">';
            echo '<div class="container-fluid main-placeholder flash-'.strtolower(self::_getType()).'">';
            echo '<img class="flash-icon" src="web+/site/img/'.strtolower(self::_getType()).'.png">&nbsp;&nbsp;<font class="flash-title">'
                    . (strtoupper(self::_getType())=='PROMPT' ? self::$Flashes[0] : self::_getCount() .' error'. (self::_getCount() > 1 ? 's':'') .' occured')
                    .'</font><br>';
            if (strtoupper(self::_getType()) == 'ERROR') {
                self::printListedFlashes();
            }
            echo '</div>';
            echo '</div>';
        }
    }

    /**
     * Adds a flash message to current flash contents.
     * @param String $flash The flash message you want to add
     * @param String $type The type of message, could be "PROMPT" or "ERROR" (or "EMPTY")
     * @param boolean $is_clearfirst Optional boolean value if existing flashes should be truncated first.
     */
    public static function addFlash($flash, $target_page, $type = 'PROMPT', $is_clearfirst = false) {
        if ($is_clearfirst) {
            self::clearFlashes();
        }
        
        if (strlen(trim($flash)) <= 0) {
            die('<br>Flash message can never be empty!');
            return false;
        }
        self::_hasFlashes();
        array_push($_SESSION[self::$FLASH_SESS_KEY], $flash);
        
        # -- Assign Content type
        $_SESSION[self::$FLASH_SESS_TYPE] = $type;        
        # -- Assign Target page
        $_SESSION[self::$FLASH_DEDICATION_KEY] = trim(strtolower($target_page));
        return true;
    }

    /**
     * Lets you add a group of flash messages.
     * @param Array $flashes Array of flash messages
     * @param String $type The type of message, could be "PROMPT" or "ERROR" (or "EMPTY")
     * @param boolean $is_clearfirst Optional boolean value if existing flashes should be truncated first.
     */
    public static function addFlashes($flashes, $target_page, $type = 'PROMPT', $is_clearfirst = false) {
        if (strtoupper(trim($type))=='PROMPT' && count($flashes) > 1) {
            die('You can only add 1 flash for prompt type of flashes!');
            return;
        }
        if ($is_clearfirst) {
            self::clearFlashes();
        }
        foreach ($flashes as $flash) {
            self::addFlash($flash, $target_page, $type);
        }
    }
    
    /**
     * An effective function to put checking protocols and auto-implemented flash contents
     * @param Array(Assoc) $a_msg_condition Format>> [ERROR_MESSAGE] => [BOOLEAN_CONDITION]
     * @param String $success_message The success message once all errors didn't exist
     * @param String $success_page The redirection page when success
     * @param String $success_page The redirection page when error
     * @param Boolean $is_clearfirst Boolean value if existing flashes shoud be cleared first
     */
    public static function checkAndAdd($a_msg_condition, $success_message, $success_page, $error_page, $is_clearfirst = false) {
        $IS_ERROR_MODE = false;
        $TYPE = 'PROMPT';
        
        if ($is_clearfirst) {
            self::clearFlashes();
        }
        
        if (count($a_msg_condition) > 0) {
            $x = 1;
            $count = count($a_msg_condition);
            do {
                // Check for occurence of an error
                if (current($a_msg_condition) && !$IS_ERROR_MODE) {
                    $IS_ERROR_MODE = true;
                    $TYPE = 'ERROR';
                }
                
                # Adds an error flash message if TRUE and ERROR_MODE
                if ($IS_ERROR_MODE && current($a_msg_condition)) {
                    self::addFlash(key($a_msg_condition), $error_page, $TYPE);
                }
                $x++;
                next($a_msg_condition);
            } while($x < $count);
        }
            
        // If not error mode, consider this flash instance as PROMPT
        //  therefore adding $success_message as a Flash message
        if (!$IS_ERROR_MODE) {
            $TYPE = 'PROMPT';
            self::addFlash($success_message, $success_page, $TYPE, true);
        }
        
    }

    /**
     * Clears the Flash contents
     */
    public static function clearFlashes() {
        $_SESSION[self::$FLASH_SESS_KEY] = array();
    }
    
    /**
     * Counts the current Flash contents
     * @return Integer
     */
    public static function _getCount() {
        return count(self::_getFlashes());
    }

    /**
     * Gets the Flash contents
     * @return Array
     */
    public static function _getFlashes() {
        if (!array_key_exists(self::$FLASH_SESS_KEY, $_SESSION)) {
            $_SESSION[self::$FLASH_SESS_KEY] = array();
        }
        if (!array_key_exists(self::$FLASH_SESS_TYPE, $_SESSION)) {
            $_SESSION[self::$FLASH_SESS_TYPE] = 'EMPTY';
        }
        
        self::$Flashes = &$_SESSION[self::$FLASH_SESS_KEY];
        return self::$Flashes;
    }
    
    /**
     * Gets the current FLASHES type
     * @return String
     */
    public static function _getType() {
        self::_hasFlashes();
        return strtoupper($_SESSION[self::$FLASH_SESS_TYPE]);
    }

    /**
     * Checks for Flashes existence
     * @return Boolean
     */
    public static function _hasFlashes() {
        $contains = false;
        if (array_key_exists(self::$FLASH_SESS_KEY, $_SESSION) && array_key_exists(self::$FLASH_SESS_TYPE, $_SESSION)) {
            $contains = count(self::_getFlashes()) > 0;
        }
        if (!$contains) {
            $_SESSION[self::$FLASH_SESS_KEY] = array();
            $_SESSION[self::$FLASH_SESS_TYPE] = 'EMPTY';
        }
        return $contains;
    }
    
    /**
     * Check if the current flash is dedicated for the current page
     * @return Boolean
     */
    public static function _isDedicatedHere() {
        if (!array_key_exists(self::$FLASH_DEDICATION_KEY, $_SESSION)) {
            $_SESSION[self::$FLASH_DEDICATION_KEY] = null;
        }
        return trim(strtolower(Index::__GetPage())) == trim(strtolower($_SESSION[self::$FLASH_DEDICATION_KEY]));
    }
    
    /**
     * FOR ERROR ONLY, prints all flash contents in LIST HTML form
     */
    public static function printListedFlashes() {
        echo '<ul>';
        foreach(self::_getFlashes() as $flashmessage) {
            echo '<li>' . $flashmessage . '</li>';
        }
        echo '</ul>';
    }

}

?>