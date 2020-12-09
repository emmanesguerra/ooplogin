<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if(Input::exists()) {
    if(Token::check(Input::get('token')))
    {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'opassword' => [
                'required' => true,
                'min' => 2,
                'max' => 20,
            ],
            'npassword' => [
                'required' => true,
            ],
            'repassword' => [
                'required' => true,
                'matches' => 'npassword',
            ]
        ]);

        if($validation->passed()) {
            
            if(!Hash::verify(Input::get('opassword'), $user->data()->password)) {
                echo 'Your old password is wrong';
            } else {
                
                try {
                    $user->update([
                        'password' =>  Hash::make(Input::get('npassword')),
                    ]);

                    Session::flash('home', 'Your password have been updated');
                    Redirect::to('index.php');

                } catch (Exception $ex) {
                    die($ex->getMessage());
                }
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
        <label for="opassword">Old Password</label>
        <input type="password" name="opassword" id="opassword" autocomplete="off" />
    </div>
    <div class="field">
        <label for="npassword">New Password</label>
        <input type="password" name="npassword" id="npassword" autocomplete="off" />
    </div>
    <div class="field">
        <label for="repassword">Re Password</label>
        <input type="password" name="repassword" id="repassword" autocomplete="off" />
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