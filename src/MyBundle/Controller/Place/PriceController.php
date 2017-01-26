<?php

namespace MyBundle\Controller\Place;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Put;
use MyBundle\Entity\Price;
use MyBundle\Form\PlaceType;
use MyBundle\Form\PriceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MyBundle\Entity\Place;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;

class PriceController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @View()
     * @Get("/places/{id}/prices")
     */
    public function getPricesAction(Request $request)
    {
        $place = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:Place')
            ->find($request->get('id'));
        /* @var $place Place */

        if(empty($place))
            return $this->placeNotFound();

        return $place->getPrices();
    }


    /**
     * @View(statusCode=Response::HTTP_CREATED)
     * @Post("/places/{id}/prices")
     */
    public function postPricesAction(Request $request)
    {
        $place = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:Place')
            ->find($request->get('id'));
        /* @var $place Place */

        if(empty($place))
            return $this->placeNotFound();

        $price = new Price();
        $price->setPlace($place);

        $form = $this->createForm(PriceType::class,$price);

        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($price);
            $em->flush();
            return $price;
        }else{
            return $form;
        }
    }

    private function placeNotFound()
    {
        return  \FOS\RestBundle\View\View::create(['message'    =>  'Place not found'], Response::HTTP_NOT_FOUND);
    }
}