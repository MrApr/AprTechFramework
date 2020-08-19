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