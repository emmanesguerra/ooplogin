<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'core/init.php';

if(!$userp = Input::get('user')) {
    Redirect::to('index.php');
} else {
    $user = new User($userp);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }
    ?>
    Fullname: <?php echo $data->name ?>

<?php
 }