<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Preference;
use AppBundle\Entity\User;
use AppBundle\Form\PreferenceType;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class PreferenceController extends Controller
{
    /**
     * @Rest\View(
     *     serializerGroups={"preference"}
     * )
     * @Rest\Get(
     *     path="/users/{id}/preferences",
     *     name="user_list_preferences"
     * )
     */
    public function getPreferencesAction(Request $request, $id)
    {
        /**
         * @var $user User
         */
        $user = $this->get("doctrine.orm.entity_manager")->getRepository('AppBundle:User')->find($id);

        if(empty($user)){
            return $this->userNotFound();
        }
        return $user->getPreferences();
    }
    /**
     * @Rest\View(
     *     statusCode=Response::HTTP_CREATED,
     *     serializerGroups={"preference"}
     * )
     * @Rest\Post(
     *     path="/users/{id}/preferences",
     *     name="user_add_preference"
     * )
     */
    public function postPreferencesAction(Request $request, $id)
    {
        $em = $this->get("doctrine.orm.entity_manager");
        /**
         * @var $user User
         */
        $user = $em->getRepository('AppBundle:User')->find($id);

        if(empty($user)){
            return $this->userNotFound();
        }
        $preference = new Preference();
        $preference->setUser($user);
        $form = $this->createForm(PreferenceType::class, $preference);
        $form->submit($request->request->all());

        if($form->isValid()){
            $em->persist($preference);
            $em->flush();
            return $preference;
        }else{
            return $form;
        }
    }

    private function userNotFound()
    {
        return View::create(['message'=>'User not found'], Response::HTTP_NOT_FOUND);
    }
}
