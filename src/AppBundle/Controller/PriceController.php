<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use AppBundle\Entity\Price;
use AppBundle\Form\PriceType;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PriceController extends Controller
{
    /**
     * @Rest\View(
     *     serializerGroups={"price"}
     * )
     * @Rest\Get(
     *     path="/places/{id}/prices",
     *     name="place_list_prices"
     * )
     */
    public function getPricesAction(Request $request, $id)
    {
        /**
         * @var $place Place
         */
        $place = $this->get("doctrine.orm.entity_manager")->getRepository('AppBundle:Place')->find($id);

        if(empty($place)){
            return $this->placeNotFound();
        }
        return $place->getPrices();
    }
    /**
     * @Rest\View(
     *     statusCode=Response::HTTP_CREATED,
     *     serializerGroups={"price"}
     * )
     * @Rest\Post(
     *     path="/places/{id}/prices",
     *     name="place_add_price"
     * )
     */
    public function postPricesAction(Request $request, $id)
    {
        /**
         * @var $place Place
         */
        $place = $this->get("doctrine.orm.entity_manager")->getRepository('AppBundle:Place')->find($id);

        if(empty($place)){
            return $this->placeNotFound();
        }

        $price = new Price();
        $price->setPlace($place);
        $form = $this->createForm(PriceType::class,$price);

        $form->submit($request->request->all());
        if($form->isValid()){
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($price);
            $em->flush();
            return $price;
        }else{
            return $form;
        }
    }

    private function placeNotFound()
    {
        return View::create(['message'=>'Place not found'], Response::HTTP_NOT_FOUND);
    }
}
