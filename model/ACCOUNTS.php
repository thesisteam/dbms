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
    
    /**
     * Create a user account entry (not including the profile)
     * @param Array(Assoc) $formdata The $_POST supplied
     * @return Boolean If success or not
     */
    public static function Create($formdata) {
        $mysql = new DB();
        
        return $mysql->InsertInto('user', array(
                'username', 'password', 'email', 'secquestion', 'secanswer', 'status', 'is_online', 'userpower_id'
            ))->Values(array(
                '"'.strtolower(DATA::__getPOST('postUsername', true, true)).'"',
                '"'.DATA::__getPOST('postPass1', true, true).'"',
                '"'.strtolower(DATA::__getPOST('postEmail', true, true)).'"',
                '"'.DATA::__getPOST('postSecquestion', true, true).'"',
                '"'.DATA::__getPOST('postSecanswer', true, true).'"',
                2,
                0,
                DATA::__getPOST('postType')
                ))->Execute()->rows_affected > 0;
    }
    
    /**
     * Create a user profile entry
     * @param Array(Assoc) $formdata The $_PO
     * @return type
     */
    public static function CreateProfile($formdata) {
        $pdosql = new DB();
        $result = $pdosql->Select('user_id')
                ->From('user')
                ->Where('`username`="'.strtolower(DATA::__getPOST('username',true,true)).'"')
                ->Query();
        if (count($result) <= 0) {
            die('Error at CreateProfile($formdata), no user exist to create a profile.');
        }
        
        # Data extraction
        $user_id = $result[0]['user_id'];
        $fname = ucfirst(DATA::__extractPost($formdata, 'postFname', true, true, true));
        $mname = ucfirst(DATA::__extractPost($formdata, 'postMname', true, true, true));
        $lname = ucfirst(DATA::__extractPost($formdata, 'postLname', true, true, true));
        $gender = DATA::__extractPost($formdata, 'postGender', true, true, true);
        $address1 = DATA::__extractPost($formdata, 'postAddress1', true, true);
        $address2 = DATA::__extractPost($formdata, 'postAddress2', true, true);
        $city = DATA::__extractPost($formdata, 'postCity', true, true);
        $province = DATA::__extractPost($formdata, 'postProvince', true, true);
        $birthdate = 'STR_TO_DATE("' . DATA::__extractPost($formdata, 'postBirthday', true, true) . '", "%m/%d/%Y")';
        $mobile = DATA::__extractPost($formdata, 'postMobile', true, true, true);
        
        return $pdosql->InsertInto("profile", array(
                'user_id', 'fname', 'mname', 'lname', 'gender', 'address1', 'address2', 'city', 'province', 'birthdate', 'mobile'
            ))->Values(array(
                $user_id, $fname, $mname, $lname, $gender, $address1, $address2, $city, $province, $birthdate, $mobile
            ))->Execute()
              ->rows_affected > 0;
    }
    
    /**
     * Check for existence of user properties
     * @param Array(Assoc) $a_field_value Assoc-array containing >> [field] => [value]
     */
    public static function Exists($a_field_value) {
        $mysql = new DB();
        return $mysql->__checkPositive('user', $a_field_value);
    }
    
    /**
     * Very simple validation of a date value according to provided mask
     * @param String $datevalue The date value to be inspected.
     * @param String $formatmask Date format masking, %% for date or month, %%%% for year (e.g. %%/%%/%%%%)
     */
    public static function ValidateDate($datevalue, $formatmask) {
        $value = trim($datevalue);
        if (strlen($value) != strlen($formatmask)) {
            return false;
        }
        for($x=0; $x<strlen($datevalue); $x++) {
            if ($datevalue[$x] != $formatmask[$x]) {
                # FALSE if current char is not numeric but the corresponding mask is %
                if (!ctype_digit($datevalue[$x]) && $formatmask[$x]=='%') {
                    return false;
                }
            } 
            else {
                # FALSE if current char is in format mask character (%)
                if ($datevalue[$x]=='%' && $formatmask[$x]=='%') {
                    return false;
                }
            }
        }
        return true;
    }
    
}
