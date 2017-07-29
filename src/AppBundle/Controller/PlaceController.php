<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use FOS\RestBundle\Controller\Annotations as Rest;
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
        $place->setName($request->get('name'));
        $place->setAddress($request->get('address'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($place);
        $em->flush();
        return $place;
    }
}
