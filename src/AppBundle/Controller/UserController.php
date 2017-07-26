<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    /**
     * @Get(
     *     path="/users",
     *     name="users_list"
     * )
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:User')->findAll();
        $formatted = [];
        foreach ($users as $user) {
            $formatted[] = [
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail()
            ];
        }
        //Récuperation du view handler
        $viewHandler = $this->get('fos_rest.view_handler');
        //Creation d'une vue FOSREST
        $view = View::create($formatted);
        $view->setFormat('json');
        //Gestion de la reponse
        return $viewHandler->handle($view);
    }
    /**
     * @Get(
     *     path="/users/{id}",
     *     name="users_one"
     * )
     */
    public function getUserAction(User $user)
    {
        if(empty($user)){
            return new JsonResponse(['message'=>'Place not found'], Response::HTTP_NOT_FOUND);
        }
        $formatted[] = [
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail()
        ];
        return new JsonResponse($formatted);
    }
}
