<?php

FLASH::clearFlashes();
$postUsername = strtolower(DATA::__GetPOST('postUsername', true, true));
$postEmail = strtolower(DATA::__GetPOST('postEmail', true, true));
$postBirthday = DATA::__GetPOST('postBirthday', false, true);

if (Index::__HasPostData()) {
    # Check for username existence
    FLASH::checkAndAdd(array(
        "Username already exists." => ACCOUNTS::Exists(array('username' => $postUsername)),
        "Passwords verification didn't match, please check again." => DATA::__GetPOST('postPass1') != DATA::__GetPOST('postPass2'),
        "Username should only contain letters and numbers" => ctype_punct($postUsername) || ctype_space($postUsername),
        "The email is already registered to an account." => ACCOUNTS::Exists(array('email' => $postEmail)),
        "The birthdate is invalid, the format should be mm/dd/yyyy." => !ACCOUNTS::ValidateDate($postBirthday, '%%/%%/%%%%')
    ), "You have successfully registered.", 
            'home',
            Index::__GetPage(),
            true);
    
    if (strtoupper(FLASH::_getType()) == 'PROMPT') {
        if (ACCOUNTS::Create($_POST)) {
            if (!ACCOUNTS::CreateProfile($_POST)) {
                $mysql = new DB();
                $userid = $mysql->Select(['id'])
                      ->From('user')
                      ->Where('`username`="' . DATA::__GetPOST($postUsername) . '"')
                      ->Query();
                if (count($userid) > 0) {
                    ACCOUNTS::Delete($userid[0]['id']);
                    FLASH::addFlash("Database error. Failed to create a profile for your account."
                            , "ERROR", true);
                }
            }
            else {
                UI::RedirectTo('home');
            }
        } 
        else {
            FLASH::addFlash("Registration failed due to a system error. Please contact the admin", 
                    Index::__GetPage(), "ERROR", true);
        }
    }
}

?>