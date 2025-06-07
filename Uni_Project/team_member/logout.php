<?php
session_start();
include('../confing/DB_connection.php');
session_unset();
session_destroy();
header('Location:../auth/logIn.php');
