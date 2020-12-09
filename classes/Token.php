<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Token
{
    public static function generate() {
        return Session::put(Config::get('session/csrf_token'), md5(uniqid()));
    }
    
    public static function check($token) {
        $tokenName = Config::get('session/csrf_token');
        
        if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
        
        return false;
    }
}