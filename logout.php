<?php
require_once "define.php";
include "module/Authentication.php";
$objLogin = new Authentication();
$objLogin->logout(PATH);

