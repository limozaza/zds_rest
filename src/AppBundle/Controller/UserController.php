<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get(
     *     path="/users",
     *     name="users_list"
     * )
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:User')->findAll();
        return $users;
    }
    /**
     * @Rest\View()
     * @Rest\Get(
     *     path="/users/{id}",
     *     name="users_one"
     * )
     */
    public function getUserAction(User $user)
    {
        if(empty($user)){
            return new JsonResponse(['message'=>'Place not found'], Response::HTTP_NOT_FOUND);
        }
        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post(
     *     path="/users",
     *     name="users_add"
     * )
     */
    public function postUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $user;
        }else {
            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete(
     *     path="/users/{id}",
     *     name="users_delete"
     * )
     */
    public function removeUserAction(User $user)
    {
        if($user) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }
    }
}
