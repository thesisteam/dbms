<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of ARRAY
 *
 * @author Allen
 */
final class ARRAYS {
    
    /**
     * Merges 2 arrays
     * @param Array $array1 (Reference)The first array and the container
     * @param Array $array2 The second array
     */
    public static function Merge(&$array1, $array2) {
        foreach($array2 as $element) {
            array_push($array1, $element);
        }
    }
    
    
}
