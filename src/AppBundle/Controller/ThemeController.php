<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use AppBundle\Entity\Theme;
use AppBundle\Form\ThemeType;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class ThemeController extends Controller
{
    /**
     * @Rest\View(
     *     serializerGroups={"theme"}
     * )
     * @Rest\Get(
     *     path="/places/{id}/themes",
     *     name="place_list_themes"
     * )
     */
    public function getThemesAction(Request $request, $id)
    {
        /**
         * @var $place Place
         */
        $place = $this->get("doctrine.orm.entity_manager")->getRepository('AppBundle:Place')->find($id);

        if(empty($place)){
            return $this->placeNotFound();
        }
        return $place->getThemes();
    }
    /**
     * @Rest\View(
     *     statusCode=Response::HTTP_CREATED,
     *     serializerGroups={"theme"}
     * )
     * @Rest\Post(
     *     path="/places/{id}/themes",
     *     name="place_add_theme"
     * )
     */
    public function postThemesAction(Request $request, $id)
    {
        $em = $this->get("doctrine.orm.entity_manager");
        /**
         * @var $place Place
         */
        $place = $em->getRepository('AppBundle:Place')->find($id);

        if(empty($place)){
            return $this->placeNotFound();
        }
        $theme = new Theme();
        $theme->setPlace($place);
        $form = $this->createForm(ThemeType::class, $theme);
        $form->submit($request->request->all());

        if($form->isValid()){
            $em->persist($theme);
            $em->flush();
            return $theme;
        }else{
            return $form;
        }
    }

    private function placeNotFound()
    {
        return View::create(['message'=>'Place not found'], Response::HTTP_NOT_FOUND);
    }
}
