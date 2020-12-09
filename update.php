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
            'name' => [
                'required' => true,
                'min' => 2,
                'max' => 20,
            ],
            'age' => [
                'required' => true,
            ],
            'points' => [
                'required' => true,
            ]
        ]);

        if($validation->passed()) {
            try {
                
                $salt = Hash::salt(20);
                
                $user->update([
                    'name' =>  Input::get('name'),
                    'age' =>  Input::get('age'),
                    'points' =>  Input::get('points'),
                ]);
                
                Session::flash('home', 'Your details have been updated');
                Redirect::to('index.php');
                
            } catch (Exception $ex) {
                die($ex->getMessage());
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
        <input type="text" name="name" id="name" value="<?php echo escape($user->data()->name); ?>" autocomplete="off" />
    </div>
    <div class="field">
        <label for="age">Age</label>
        <input type="text" name="age" id="age" value="<?php echo escape($user->data()->age); ?>" autocomplete="off" />
    </div>
    <div class="field">
        <label for="points">Points</label>
        <input type="text" name="points" id="points" value="<?php echo escape($user->data()->points); ?>" autocomplete="off" />
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