<?php

$a_pendinglist = ACCOUNTS::getPendingUsers(array(
            'user.id', 'userpower_id', 'username', 'concat(fname, \' \', lname)'
                ), true);
$rptPendingusers = new MYSQLREPORT();
$rptPendingusers->setReportProperties(array(
            'width' => '100%',
            'align' => 'left'))
        ->setReportHeaders(array(
            [
                'CAPTION' => 'hidden_ID',
                'HIDDEN' => true
            ], [
                'CAPTION' => 'hidden_TYPE',
                'HIDDEN' => true
            ], [
                'CAPTION' => 'Username',
                'class' => 'rpt-header-noborder',
                'width' => '20%'
            ], [
                'CAPTION' => 'Full name',
                'class' => 'rpt-header-noborder',
                'width' => '30%'
            ], [
                'CAPTION' => 'Action',
                'DEFAULT' => 
                    UI::Button('Approve', 'button', 'btn btn-warning btn-xs', 
                            UI::GetPageUrl('admin-action-pendings', array(
                                'who' => '{1}',
                                'approve' => 'true'
                            )), false) . '&nbsp;' .
                    UI::Button('Reject', 'button', 'btn btn-danger btn-xs',
                            UI::GetPageUrl('admin-action-pendings', array(
                                'who' => '{1}',
                                'approve' => 'false'
                            )), false),
                'class' => 'rpt-header-noborder',
                'width' => '50%'
            ]
        ))
        ->setReportCellstemplate(array(
            [], [], [
                'class' => 'rpt-cell'
            ], [], [], []
        ));
$rptPendingusers->loadResultdata($a_pendinglist);
?>