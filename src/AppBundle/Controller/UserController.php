<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends FOSRestController
{
    /**
     * @Rest\View(
     *     serializerGroups={"user"}
     * )
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
     * @Rest\View(
     *     serializerGroups={"user"}
     * )
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
     * @Rest\View(
     *     statusCode=Response::HTTP_CREATED,
     *     serializerGroups={"user"}
     * )
     * @Rest\Post(
     *     path="/users",
     *     name="users_add"
     * )
     */
    public function postUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user,
            [
                'validation_groups' => ['Default', 'New']
            ]
            );
        $form->submit($request->request->all());

        if($form->isValid()){
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return View::create(
                $user,
                Response::HTTP_CREATED,
                [
                    'Location' => $this->generateUrl('users_one',['id'=>$user->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
                ]
            );
        }else {
            return $form;
        }
    }
    /**
     * @Rest\View(
     *     statusCode=Response::HTTP_NO_CONTENT,
     *     serializerGroups={"user"}
     * )
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
    /**
     * @Rest\View(
     *     statusCode=Response::HTTP_OK,
     *     serializerGroups={"user"}
     * )
     * @Rest\Put(
     *     path="/users/{id}",
     *     name="users_put"
     * )
     */
    public function putUserAction(Request $request,User $user)
    {
        $this->updateUser($request,$user,true);
    }
    /**
     * @Rest\View(statusCode=Response::HTTP_OK,serializerGroups={"user"})
     * @Rest\Patch(
     *     path="/users/{id}",
     *     name="users_patch"
     * )
     */
    public function patchUserAction(Request $request,User $user)
    {
        $this->updateUser($request,$user,false);
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK,serializerGroups={"place"})
     * @Rest\Get(
     *     path="/users/{id}/suggestions",
     *     name="users_suggestions"
     * )
     */
    public function getUserSuggestionsAction(Request $request, $id)
    {
        /**
         * @var $user User
         */
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($id);

        if(empty($user)){
            return View::create(['message'=>'User not found'],Response::HTTP_NOT_FOUND);
        }
        $suggestions= [];
        $places = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Place')
            ->findAll();
        foreach ($places as $place){
            if($user->preferencesMatch($place->getThemes())){
                $suggestions[] = $place;
            }
        }
        return $suggestions;
    }


    private function updateUser(Request $request,User $user, $clearMissing)
    {
        if(empty($user)){
            return View::create(['message'=>'Place not found'], Response::HTTP_NOT_FOUND);
        }
        if($clearMissing){
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        }else{
            $options = [];
        }


        $form = $this->createForm(UserType::class, $user, $options);
        $form->submit($request->request->all(),$clearMissing);
        if($form->isValid()){
            if(!empty($user->getPlainPassword())){
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user,$user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $user;
        }else{
            return $form;
        }
    }
}
