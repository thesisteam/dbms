<?php

$targetpage404 = null;
$malicious404 = 'was not found on this server.';
if (array_key_exists('target', $_GET)) {
    $targetpage404 = strtolower(trim($_GET['target']));
    // Recheck for target page existence
    if (Index::__HasPage($targetpage404) && Index::__HasScript($targetpage404)) {
        UI::RedirectTo($targetpage404);
    }
}
if (array_key_exists('malicious', $_GET)) {
    if ($_GET['malicious'] == 'yes') {
        $malicious404 = 'is an invalid page name.';
    }
}