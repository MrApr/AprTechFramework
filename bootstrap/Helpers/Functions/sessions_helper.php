<?php

function setSession(string $key, $value)
{
    if(!isset($_SESSION[$key]))
    {
        $_SESSION[$key] = $value;
    }
}

function destroyAllSessions(string $except = "user")
{
    foreach ($_SESSION as $key => $value)
    {
        if($key !== $except)
        {
            unset($_SESSION[$key]);
        }
    }
}
function sessionCheck(string $key)
{
    if(isset($_SESSION[$key]) && $_SESSION[$key])
    {
        return true;
    }
}

function getSession(string $key)
{
    if(sessionCheck($key))
    {
        $value = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $value;
    }
}

function redirect(string $path)
{
    header("location: ".URL."/".trim($path,'/'));
}