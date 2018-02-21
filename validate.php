<?php

function min_length($str, $len)
{
    if (mb_strlen($str) <= $len) {
        return true;
    } else {
        return false;
    }
}

function max_length($str, $len)
{
    if (mb_strlen($str) >= $len) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST["login"])) {
    $err = [];

    if (!$_POST['uname']) {
        $err['uname'] = 'Name is missing.';
    }
    if (!$_POST['upassword']) {
        $err['upassword'] = 'Password is not entered.';
    }
}

if (isset($_POST["signup"])) {
    $err = [];

    if (!$_POST['uname']) {
        $err['uname'] = 'Name is missing.';
    } elseif (min_length($_POST['uname'], 4)) {
        $err['uname'] = 'Must be at least 4 characters long.';
    }
    if (!$_POST['upassword']) {
        $err['upassword'] = 'Password is not entered.';
    } elseif (max_length($_POST['upassword'], 10)) {
        $err['upassword'] = 'Must be 10 characters or less.';
    }
}
