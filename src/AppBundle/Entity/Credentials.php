<?php
/**
 * Created by PhpStorm.
 * User: zakaria
 * Date: 11/08/17
 * Time: 10:40
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation as Serializer;


class Credentials
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    protected $login;
    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    protected $password;

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


}