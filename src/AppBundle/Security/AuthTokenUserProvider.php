<?php
/**
 * Created by PhpStorm.
 * User: zakaria
 * Date: 16/10/17
 * Time: 21:05
 */

namespace AppBundle\Security;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthTokenUserProvider implements UserProviderInterface
{
    protected $authTokenRepository;
    protected $userRepository;

    public function __construct(EntityRepository $authTokenRepository, EntityRepository $userRepository)
    {
        $this->authTokenRepository = $authTokenRepository;
        $this->userRepository = $userRepository;
    }

    public function getAuthToken($authTokenHeader)
    {
        return $this->authTokenRepository->findOneByValue($authTokenHeader);
    }

    public function loadUserByUsername($email)
    {
        return $this->userRepository->findByEmail($email);
    }
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }
    public function supportsClass($class)
    {
        return 'AppBundle\Entity\User'=== $class;
    }
}