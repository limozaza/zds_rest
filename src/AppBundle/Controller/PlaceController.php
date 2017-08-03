<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use AppBundle\Form\PlaceType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlaceController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get(
     *     path="/places",
     *     name="places_list"
     * )
     */
    public function getPlacesAction()
    {
        $places = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:Place')->findAll();
        return $places;
    }
    /**
     * @Rest\View()
     * @Rest\Get(
     *     path="/places/{id}",
     *     name="places_one"
     * )
     */
    public function getPlaceAction(Place $place)
    {
        if(empty($place)){
            return new JsonResponse(['message'=>'Place not found'], Response::HTTP_NOT_FOUND);
        }
        return $place;
    }
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post(
     *     path="/places",
     *     name="places_add"
     * )
     */
    public function postPlacesAction(Request $request)
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all());
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();
            return $place;
        }else{
            return $form;
        }
    }
    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete(
     *     path="/places/{id}",
     *     name="places_delete"
     * )
     */
    public function removePlaceAction(Place $place)
    {
        if($place) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($place);
            $em->flush();
        }
    }
    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Put(
     *     path="/places/{id}",
     *     name="places_put"
     * )
     */
    public function putPlaceAction(Request $request,Place $place)
    {
        $this->updatePlace($request,$place,true);
    }
    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Patch(
     *     path="/places/{id}",
     *     name="places_patch"
     * )
     */
    public function patchPlaceAction(Request $request,Place $place)
    {
        $this->updatePlace($request,$place,false);
    }
    private function updatePlace(Request $request,Place $place, $clearMissing)
    {
        if(empty($place)){
            return View::create(['message'=>'Place not found'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(PlaceType::class, $place);
        $form->submit($request->request->all(),$clearMissing);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $place;
        }else{
            return $form;
        }
    }

}
