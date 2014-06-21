<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$targetpage404 = null;
$malicious404 = 'was not found on this server.';
if (array_key_exists('target', $_GET)) {
    $targetpage404 = $_GET['target'];
}
if (array_key_exists('malicious', $_GET)) {
    if ($_GET['malicious'] == 'yes') {
        $malicious404 = 'is an invalid page name.';
    }
}