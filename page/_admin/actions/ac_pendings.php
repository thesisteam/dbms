<?php

DATA::GenerateIntentsFromGET();
print_r($_SESSION, true);
$id = DATA::__GetIntent('WHO');
$is_approve = trim(strtolower(DATA::__GetIntent('IS_APPROVE')))=='true' ? true : false;

$mysql = new DB();
$mysql->Update('user')
        ->Set(array(
            'status' => $is_approve ? 0 : 1
        ))
        ->Where('user.id='.$id);
$is_success = $mysql->Execute()->__GetAffectedRows() > 0;
if ($is_success) {
    FLASH::addFlash('User "' . DB::__getSubstitute($id, 'user', 'username') . '" has been successfully ' . ($is_approve ? 'approved' : 'rejected'),
            ['admin-site-config', 'home'], 'PROMPT', true);
    UI::RedirectTo('admin-site-config');
    DATA::closePassage();
} else {
    echo $mysql->Lasterror;
}

?>