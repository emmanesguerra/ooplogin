<?php 

require_once 'core/init.php';

if(Input::exists()) {
    if(Token::check(Input::get('token')))
    {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'name' => [
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users',
            ],
            'password' => [
                'required' => true,
                'min' => 6,
            ],
            'repassword' => [
                'required' => true,
                'matches' => 'password',
            ],
            'age' => [
                'required' => true,
            ],
            'points' => [
                'required' => true,
            ]
        ]);

        if($validation->passed()) {
            $user = new User();
            try {
                
                $salt = Hash::salt(20);
                
                $user->create([
                    'name' =>  Input::get('name'),
                    'password' =>  Hash::make(Input::get('password'), $salt),
                    'salt' =>  $salt,
                    'age' =>  Input::get('age'),
                    'points' =>  Input::get('points'),
                ]);
                
                Session::flash('home', 'You have been registered and can now log in');
                Redirect::to(404);
                
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
        <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>" autocomplete="off" />
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?php echo escape(Input::get('password')); ?>" autocomplete="off" />
    </div>
    <div class="field">
        <label for="repassword">Password Again</label>
        <input type="password" name="repassword" id="repassword" value="<?php echo escape(Input::get('repassword')); ?>" autocomplete="off" />
    </div>
    <div class="field">
        <label for="age">Age</label>
        <input type="text" name="age" id="age" value="<?php echo escape(Input::get('age')); ?>" autocomplete="off" />
    </div>
    <div class="field">
        <label for="points">Points</label>
        <input type="text" name="points" id="points" value="<?php echo escape(Input::get('points')); ?>" autocomplete="off" />
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