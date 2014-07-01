<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require '../model/ACCOUNTS.php';
require '../util/PDOSQL.php';

if (count($_POST) > 0) {
    $pdoresult = ACCOUNTS::Create($_POST);
    if ($pdoresult->rows_affected > 0) {
        die('Great! You have successfully registered!');
    } else {
        die('Oh no, registration failed ^_^');
    }
}