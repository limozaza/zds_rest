<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PlaceController extends Controller
{
    /**
     * @Route("/places", name="places_list")
     * @Method({"GET"})
     */
    public function getPlacesAction()
    {
        $places = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:Place')->findAll();

        $formatted = [];
        foreach ($places as $place) {
            $formatted[] = [
                'id' => $place->getId(),
                'name' => $place->getName(),
                'address' => $place->getAddress(),
            ];
        }

        return new JsonResponse($formatted);
    }

    /**
     * @Route("/places/{id}", name="places_one")
     * @Method({"GET"})
     */
    public function getPlaceAction(Place $place)
    {
        if(empty($place)){
            return new JsonResponse(['message'=>'Place not found'], Response::HTTP_NOT_FOUND);
        }
        $formatted[] = [
            'id' => $place->getId(),
            'name' => $place->getName(),
            'address' => $place->getAddress(),
        ];

        return new JsonResponse($formatted);
    }
}
