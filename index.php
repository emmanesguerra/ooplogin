<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'core/init.php';

if(Session::exists('home')) {
    echo Session::flash('home');
}

$user = new User();
if($user->isLoggedIn()) {
?>
    <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->name) ?>"><?php echo escape($user->data()->name) ?></a></p>
    <ul>
        <li><a href="update.php">Update Details</a></li>
        <li><a href="updatepassword.php">Change password</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
<?php

    if($user->hasPermission('admin')) {
        echo 'You are an administrator';
    }

} else {
    echo '<p>You need to <a href="login.php">login</a> or <a href="register.php">register</a></p>';
}

//$users = DB::getInstance()->query('SELECT name FROM users WHERE name = ? or name = ? or name = ?', ['emman', 'andrew', 'darwin']);
//$users = DB::getInstance()->query('SELECT name FROM users');
//$users = DB::getInstance()->get('users', array('name', '=', 'darwin'));

//if(!$users->count()) {
//    echo 'NO user';
//} else {
//    echo $users->first()->name;
//}

//
//if($users->count()) {
//    foreach ($users as $user) {
//        echo $user->username;
//    }
//}

//$userInsert = DB::getInstance()->insert('users', [
//    'name' => 'Dale',
//    'age' => '5',
//    'points' => 100
//]);
//
//if($userInsert) {
//    echo 'Inserted';
//}

//$userInsert = DB::getInstance()->update('users', 5, [
//    'name' => 'Dale',
//    'age' => '5',
//    'points' => 95
//]);
//
//if($userInsert) {
//    echo 'Updated';
//}