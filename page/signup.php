<?php
FLASH::clearFlashes();

$postUsername = strtolower(DATA::__getPOST('postUsername', true, true));
$postEmail = strtolower(DATA::__getPOST('postEmail', true, true));
$postBirthday = DATA::__getPOST('postBirthday', false, true);

if (Index::__HasPostData()) {
    # Check for username existence
    FLASH::checkAndAdd(array(
        "Username already exists." => ACCOUNTS::Exists(array('username' => $postUsername)),
        "Passwords verification didn't match, please check again." => DATA::__getPOST('postPass1') != DATA::__getPOST('postPass2'),
        "Username should only contain letters and numbers" => ctype_punct($postUsername) || ctype_space($postUsername),
        "The email is already registered to an account." => ACCOUNTS::Exists(array('email' => $postEmail)),
        "The birthdate is invalid, the format should be mm/dd/yyyy." => !ACCOUNTS::ValidateDate($postBirthday, '%%/%%/%%%%')
    ), "You have successfully registered.", true);
    
    if (strtoupper(FLASH::_getType()) == 'PROMPT') {
        ACCOUNTS::Create($_POST);
        ACCOUNTS::CreateProfile($_POST);
    }
}

?>