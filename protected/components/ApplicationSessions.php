<?php

/*
* this is a common class which is used for storing and retriving values from sessions
* making it common would help in addressing two problems
* 1. Code written would be less
* 2. In case we need to store sessions to different location (e.g. DB, or Memcache) there would be 
* minumum change in applications
*/
class ApplicationSessions
{

    private static $session;

    private function __construct()
    {
    }

    public static function run()
    {
        if (!isset(self::$session))
        {
            $session = new CHttpSession;
            $session->open();
            $c = __class__;
            self::$session = new $c;
            self::$session->session_var = $session;
        }
        return self::$session;
    }


    public function read($key)
    {
        if (isset($this->session_var[$key]))
        {
            return $this->session_var[$key];
        } else
        {
            return "";
        }
    }
    public function write($key, $value)
    {
        $this->session_var[$key] = $value;
    }

    public function delete($key)
    {
        $this->session_var->remove($key);
    }

    public function deleteAll()
    {
        $this->session_var->clear();
    }

}
