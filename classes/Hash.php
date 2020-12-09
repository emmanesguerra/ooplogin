<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Hash
{
    public static function make($string) {
        return password_hash($string, PASSWORD_DEFAULT);
    }
    public static function verify($string, $hash) {
        return password_verify($string, $hash);
    }
    
    public static function salt($length) {
        return bin2hex(random_bytes($length));
    }
    
    public static function unique() {
        return self::make(uniqid());
    }
}

