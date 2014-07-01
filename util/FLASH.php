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
    public static $FLASH_SESS_TYPE = '_flash_type';     # Session key for flash type
    public static $Flashes = array();

    /**
     * The main function to be called when showing Flashes
     */
    public static function Initialize() {
        if (self::hasFlashes()) {
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
    public static function addFlash($flash, $type = 'PROMPT', $is_clearfirst = false) {
        if ($is_clearfirst) {
            self::clearFlashes();
        }
        
        if (strlen(trim($flash)) <= 0) {
            die('<br>Flash message can never be empty!');
            return false;
        }
        self::hasFlashes();
        array_push($_SESSION[self::$FLASH_SESS_KEY], $flash);
        $_SESSION[self::$FLASH_SESS_TYPE] = $type;
        return true;
    }

    /**
     * Lets you add a group of flash messages.
     * @param Array $flashes
     * @param String $type The type of message, could be "PROMPT" or "ERROR" (or "EMPTY")
     * @param boolean $is_clearfirst Optional boolean value if existing flashes should be truncated first.
     */
    public static function addFlashes($flashes, $type = 'PROMPT', $is_clearfirst = false) {
        if (strtoupper(trim($type))=='PROMPT' && count($flashes) > 1) {
            die('You can only add 1 flash for prompt type of flashes!');
            return;
        }
        if ($is_clearfirst) {
            self::clearFlashes();
        }
        foreach ($flashes as $flash) {
            self::addFlash($flash, $type);
        }
    }

    public static function clearFlashes() {
        $_SESSION[self::$FLASH_SESS_KEY] = array();
    }
    
    public static function _getCount() {
        return count(self::_getFlashes());
    }

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
    
    public static function _getType() {
        self::hasFlashes();
        return $_SESSION[self::$FLASH_SESS_TYPE];
    }

    public static function hasFlashes() {
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
    
    public static function printListedFlashes() {
        echo '<ul>';
        foreach(self::_getFlashes() as $flashmessage) {
            echo '<li>' . $flashmessage . '</li>';
        }
        echo '</ul>';
    }

}
