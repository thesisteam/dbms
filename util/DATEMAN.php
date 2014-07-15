<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Static utility class for Date manipulation
 * @author Allen
 */
final class DATEMAN {
    
    public static function getDate() {
        return date('Y-m-d');
    }
    
    public static function getMonth() {
        return date('m');
    }
    
    /**
     * Return the current standard date and time<br>
     * FORMAT: YYYY-MM-DD 24HH:MM:SS (PHP: 'Y-m-d H:i:s')
     * @return type
     */
    public static function getStdDatetime() {
        return date('Y-m-d H:i:s', time());
    }
    
    public static function getTime() {
        return date('H:i:s');
    }
    
    public static function getYear() {
        return date('Y');
    }
}
