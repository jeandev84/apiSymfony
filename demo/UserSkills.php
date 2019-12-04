<?php
namespace Framework;

function debug($var, $die = false)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    if($die) die;
}

/**
 * Interface UserInterface
 * @package Framework
*/
interface UserInterface
{
     /** @return UserInterface */
     public function getUser();
}


/**
 * Class User
 * @package Framework
*/
class User implements UserInterface
{
    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /** @var string */
    private $surname;

    /**
     * @return $this
    */
    public function getUser()
    {
         return $this;
    }
}


