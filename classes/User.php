<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User
{
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;
    
    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');
        
        if(!$user) {
            if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                
                if($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    //process logout
                }
            }
        } else {
            $this->find($user);
        }
    }
    
    public function create($fields = array()) {
        if(!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem creating new account');
        }
    }
    
    public function update($fields = array(), $id = null) {
        
        if(!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }
        
        if(!$this->_db->update('users', $id ,$fields)) {
            throw new Exception('There was a problem updating account');
        }
    }
    
    public function find($user = null) {
        if($user) {
            $field = (is_numeric($user)) ? 'id' : 'name';
            $data = $this->_db->get('users', [$field, '=', $user]);
            
            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
    }
    
    public function login($username = null, $password = null, $remember = false) {
        
        if(!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($username);
        
            if($user) {
                if(Hash::verify($password, $this->data()->password)) {
                    Session::put($this->_sessionName, $this->data()->id);

                    if($remember) {
                        $hash = Hash::unique();
                        $hashChecked = $this->_db->get('user_session', ['user_id', '=', $this->data()->id]);

                        if(!$hashChecked->count()) {
                            $this->_db->insert('user_session', [
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ]);
                        } else {
                            $hash = $hashChecked->first()->hash;
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }

                    return true;
                }
            }
        }

        return false;
    }
    
    public function hasPermission($key) {
        $group = $this->_db->get('groups', ['id', '=', $this->data()->group]);
        
        if($group->count()) {
            $permissions = json_decode($group->first()->permission, true);
            
            if($permissions[$key] == true) {
                return true;
            }
        }
        
        return false;
    }
    
    public function exists() {
        return (!empty($this->_data)) ? true: false;
    }
    
    public function logout() {
        
        $this->_db->delete('user_session', ['user_id', '=', $this->data()->id]);
        
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }
    
    public function data() {
        return $this->_data;
    }
    
    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }
}