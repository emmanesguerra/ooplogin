<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'core/init.php';


if(Input::exists()) {
    if(Token::check(Input::get('token')))
    {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'name' => [
                'required' => true,
            ],
            'password' => [
                'required' => true,
            ]
        ]);

        if($validation->passed()) {
            $user = new User();
            
            $remember = (Input::get('remember') === 'on') ? true: false;
            $login = $user->login(Input::get('name'), Input::get('password'), $remember);
            
            if($login) {
                Redirect::to('index.php');
            } else {
                echo 'Failed';
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br />';
            }
        }
    } else {
        echo 'Invalid CSRF TOKEN no refreshing';
    }
}

?>

<form action="" method="post">
    <div class="field">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>" autocomplete="off" />
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?php echo escape(Input::get('password')); ?>" autocomplete="off" />
    </div>
    <div class="field">
        <label for="remember">
            <input type="checkbox" name="remember" id="remember"/> Remember me
        </label>
    </div>
    <div class="field">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="submit" valu="Submit"/>
    </div>
</form>

<style>
    .field {
        float: left;
        width: 90%;
        padding: 5px;
    }
    label {
        float: left;
        width: 10%;
    }
</style>