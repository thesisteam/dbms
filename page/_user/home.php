<?php

DATA::openPassage();
DATA::CreateIntent('name', 'Allen Linatoc');
print_r($_SESSION);
UI::NewLine();
DATA::closePassage();
print_r($_SESSION);

?>