<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ACCOUNTS
 *
 * @author Allen
 */
final class ACCOUNTS {
    
    public static function Create($formdata) {
        $pdosql = new PDOSQL();
        
        return $pdosql->InsertInto('user', array(
                'username', 'password', 'email', 'secquestion', 'secanswer', 'status', 'is_online', 'userpower_id'
            ))->Values(array(
                '"'.$formdata['postUsername'].'"',
                '"'.$formdata['postPass1'].'"',
                '"'.$formdata['postEmail'].'"',
                '"'.$formdata['postSecquestion'].'"',
                '"'.$formdata['postSecanswer'].'"',
                2,
                0,
                1
                ));
    }
    
}
