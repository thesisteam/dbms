<?php

if (DATA::__HasPostData()) {
    $postUsername = DATA::__GetPOST('postUsername', true, true, true);
    $postPassword = DATA::__GetPOST('postPassword', true, true);
    FLASH::checkAndAdd(array(
        'Username should only contain letters and numbers' => ctype_punct($postUsername) || ctype_space($postUsername)),
            'Validation success! Developers should add data redirection in this :D', 
            Index::__GetPage(), Index::__GetPage(), true);

    if (FLASH::_getType() == 'PROMPT') {
        $auth_result = ACCOUNTS::Authenticate($postUsername, $postPassword);
        if ($auth_result['IS_SUCCESS']) {
            UI::RedirectTo(USER::GetLandpage());
        }
        else {
            FLASH::addFlash('Wrong username or password, please try again',
                    Index::__GetPage(), 'ERROR', true);
        }
    }
}
?>