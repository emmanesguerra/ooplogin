<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'core/init.php';

$user = new User();
$user->logout();

Redirect::to('index.php');

