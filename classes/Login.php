<?php

class Login
{
    private $_db,
        $_data,
        $_sessionName,
        $_cookieName,
        $_isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = new DB();
        $this->_sessionName = 'user';
        $this->_cookieName = 'hash';
        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->findUserID($user)) {
                    $this->_isLoggedIn = true;
                } else {
//                    echo '###<br>####<br>####<br>###';
                    //proces logout
                }
            }
        } else {
            $this->findUserID($user);
        }
    }

    public function findUserName($userName = null)
    {
        if ($userName) {
            $data = $this->_db->query('select * from users where username=:username', array(':username' => $userName), PDO::FETCH_OBJ);
            if (count($data) > 0) {
                $this->_data = $data[0];
                return true;
            }
        }
        return false;
    }

    public function findUserID($userID = null)
    {
        if ($userID) {
            $data = $this->_db->query('select * from users where id=:id', array(':id' => $userID), PDO::FETCH_OBJ);
            if (count($data) > 0) {
                $this->_data = $data[0];
                return true;
            }
        }
        return false;
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->_data->id);
        } else {
            $user = $this->findUserName($username);
            if ($user) {
                if ($this->_data->password === Hash::make($password, $this->_data->salt)) {
                    Session::put($this->_sessionName, $this->_data->id);
                    if ($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->query('select * from users_session where user_id=:user_id', array(':user_id' => $this->_data->id));
                        if (count($hashCheck) === 0) {
                            $this->_db->zInsert('users_session', array(
                                'user_id' => $this->_data->id,
                                'hash'    => $hash
                            ));
                        } else {
                            $hash = $hashCheck[0]->hash;
                        }
                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }
                    $this->setSessions();
                    return true;
                }
            }
        }
        return false;
    }

    public function setSessions()
    {
        $userId = @$this->_data->id;
        if ($userId) {
            Session::put('current_user_id', $this->_data->id);
            Session::put('current_user_group', $this->_data->user_group);
            Session::put('current_user_name', $this->_data->name1 . ' ' . $this->_data->name2 . ' ' . $this->_data->name3 . ' ' . $this->_data->name4);
        }
//        Session::put(Config::get('session/rand_key'),substr(md5(rand()), 0, 32));
    }

    public function hasPermission($groupNames)
    {
        $this->setSessions();
        $user_group = @$this->_data->user_group;
        if ($user_group) {
            $groups = $this->_db->query('select * from groups where id=:id', array(':id' => $user_group));
            if (count($groups) > 0) {
                $groupNames = explode(',', $groupNames);
                $permissions = explode(',', $groups[0]->permission);
                foreach ($groupNames as $groupName) {
                    if (in_array($groupName, $permissions)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function exists()
    {
        return (!empty($this->_data) ? true : false);
    }

    public function logout()
    {
        $userID = @$this->_data->id;
        if ($userID) {
            $this->_db->query('delete from users_session where user_id=:user_id', array(':user_id' => $this->_data->id));
            Session::delete($this->_sessionName);
            Cookie::delete($this->_cookieName);
        }
    }

    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}

?>
