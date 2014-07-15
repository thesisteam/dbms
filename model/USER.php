<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Static class to hold user session informations
 *
 * @author Allen
 */
final class USER {
    
    const _USER_CREDENTIALS_MAIN_KEY = 'ass_user_credentials';
    const USERNAME = 'uc_username';
    const PASSWORD = 'uc_password';
    const LASTLOGIN = 'uc_lastlogin';
    const LASTLOGOUT = 'uc_lastlogout';
    
    /**
     * Initializes the User credential session<br>
     * Does nothing if credential already exists
     */
    public static function InitSession($reset_values=false) {
        if (!key_exists(self::_USER_CREDENTIALS_MAIN_KEY, $_SESSION) || $reset_values) {
            $_SESSION[self::_USER_CREDENTIALS_MAIN_KEY] = array(
                self::USERNAME => null,
                self::PASSWORD => null,
                self::LASTLOGIN => null,
                self::LASTLOGOUT => null
            );
        }
    }
    
    /**
     * Destroy all the user credential session data
     */
    public static function DestroySession() {
        if (key_exists(self::_USER_CREDENTIALS_MAIN_KEY, $_SESSION)) {
            unset($_SESSION[self::_USER_CREDENTIALS_MAIN_KEY]);
        }
    }
    
    /**
     * Return the value of a 'user credential key'
     * @param String $uc_key The 'user credential key', i.e: <i>USER::USERNAME</i>
     * @return mixed The corresponding value to the user credential key
     */
    public static function Get($uc_key) {
        self::InitSession();
        return $_SESSION[self::_USER_CREDENTIALS_MAIN_KEY][$uc_key];
    }
    
    /**
     * Reset the user credential session data
     */
    public static function ResetSession() {
        self::InitSession(true);
    }
    
    /**
     * Set the value of a user credential key
     * @param String $uc_key The user credential key
     * @param String $value The value to be assigned to the 'user credential key'
     */
    public static function Set($uc_key, $value) {
        self::InitSession();
        $_SESSION[self::_USER_CREDENTIALS_MAIN_KEY][$uc_key] = $value;
    }
    
}
