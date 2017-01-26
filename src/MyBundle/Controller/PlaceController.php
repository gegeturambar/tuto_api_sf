<?php

namespace MyBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Put;
use MyBundle\Form\PlaceType;
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

class PlaceController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPlacesAction(Request $request)
    {
        $places = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:Place')
            ->findAll();
        /* @var $places Place[] */

        return $places;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPlaceAction($id, Request $request)
    {
        $place = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:Place')
            ->find($id);
        /* @var $place Place */
        if (empty($place)) {
            throw $this->createNotFoundException();
        }
        return $place;
    }

    /**
     * @View(statusCode=Response::HTTP_CREATED)
     * @Post("/places")
     */
    public function postPlacesAction(Request $request)
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class,$place);

        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($place);
            $em->flush();
            return $place;
        }else{
            return $form;
        }

    }

    /**
     * @View(statusCode=Response::HTTP_NO_CONTENT)
     * @Delete("/places/{id}")
     * @param Request $request
     */
    public function deletePlaceAction(Request $request){
        $place = $this->getDoctrine()->getRepository("MyBundle:Place")->find($request->get('id'));
        /* @var $place Place */
        if($place) {
            $em = $this->getDoctrine()->getEntityManager();
            foreach ($place->getPrices() as $price){
                $price->remove($place);
            }
            $em->remove($place);
            $em->flush();
        }

    }

    /**
     * @View()
     * @Put("/places/{id}")
     */
    public function putPlaceAction(Request $request)
    {
        return $this->updatePlace($request);
    }

    /**
     * @View()
     * @Patch("/places/{id}")
     * @param Request $request
     */
    public function patchPlaceAction(Request $request){
        return $this->updatePlace($request,false);
    }

    private function updatePlace(Request $request,$clearMissing = true ){
        $place = $this->getDoctrine()->getRepository("MyBundle:Place")->find($request->get('id'));
        if(empty($place)){
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(PlaceType::class,$place);
        $form->submit($request->request->all(), $clearMissing);
        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($place);
            $em->flush();
            return $place;
        }else{
            return $form;
        }
    }
}