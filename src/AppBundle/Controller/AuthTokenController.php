<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AuthToken;
use AppBundle\Entity\Credentials;
use AppBundle\Form\CredentialsType;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthTokenController extends Controller
{
    /**
     * @Rest\View(
     *     statusCode=Response::HTTP_CREATED,
     *     serializerGroups={"auth-token"}
     * )
     * @Rest\Post(
     *     path="/auth-tokens",
     *     name="auth-tokens-add"
     * )
     */
    public function postAuthTokens(Request $request)
    {
        $credentials = new Credentials();
        $form = $this->createForm(CredentialsType::class, $credentials);
        $form->submit($request->request->all());
        if(!$form->isValid()){
            return $form;
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')->findOneByEmail($credentials->getLogin());
        if(!$user){
            return $this->invalidCredentials();
        }
        $encoder = $this->get('security.password_encoder');
        $isPasswordValid = $encoder->isPasswordValid($user, $credentials->getPassword());
        if(!$isPasswordValid){
            return $this->invalidCredentials();
        }

        $authToken = new AuthToken();
        $authToken->setValue(base64_encode(random_bytes(50)));
        $authToken->setCreatedAt(new \DateTime('now'));
        $authToken->setUser($user);

        $em->persist($authToken);
        $em->flush();
        return $authToken;
    }

    private function invalidCredentials()
    {
        return View::create(['message'=> 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
    }

}
