<?php

// Prepare to count all Pending users
$mysql = new DB();
$result = $mysql->Select(['count(*) as "PENDING_COUNT"'])
        ->From('user')
        ->Where('user.status=2')
        ->Query();
$pending_count = intval($result[0]['PENDING_COUNT']);
?>