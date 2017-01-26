<?php

namespace MyBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Put;
use MyBundle\Form\ThemePlaceMapType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MyBundle\Entity\ThemePlaceMap;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;

class ThemePlaceMapController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getThemePlaceMapsAction(Request $request)
    {
        $themePlaceMaps = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:ThemePlaceMap')
            ->findAll();
        /* @var $themePlaceMaps ThemePlaceMap[] */

        return $themePlaceMaps;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getThemePlaceMapAction($id, Request $request)
    {
        $themePlaceMap = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:ThemePlaceMap')
            ->find($id);
        /* @var $themePlaceMap ThemePlaceMap */
        if (empty($themePlaceMap)) {
            throw $this->createNotFoundException();
        }
        return $themePlaceMap;
    }

    /**
     * @View(statusCode=Response::HTTP_CREATED)
     * @Post("/themePlaceMaps")
     */
    public function postThemePlaceMapsAction(Request $request)
    {
        /*
        $theme = $this->getDoctrine()->getRepository("MyBundle:Theme")->find($request->get('theme'));
        if(empty($theme))
            $this->createNotFoundException('theme not found');

        $place = $this->getDoctrine()->getRepository("MyBundle:Place")->find($request->get('place'));
        if(empty($place))
            $this->createNotFoundException('place not found');
*/
        $themePlaceMap = new ThemePlaceMap();

        /*
        $themePlaceMap->setTheme($theme);
        $themePlaceMap->setPlace($place);
        */


        $form = $this->createForm(ThemePlaceMapType::class,$themePlaceMap);

        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($themePlaceMap);
            $em->flush();
            return $themePlaceMap;
        }else{
            return $form;
        }

    }

    /**
     * @View(statusCode=Response::HTTP_NO_CONTENT)
     * @Delete("/themePlaceMaps/{id}")
     * @param Request $request
     */
    public function deleteThemePlaceMapAction(Request $request){
        $themePlaceMap = $this->getDoctrine()->getRepository("MyBundle:ThemePlaceMap")->find($request->get('id'));
        /* @var $themePlaceMap ThemePlaceMap */
        if($themePlaceMap) {
            $em = $this->getDoctrine()->getEntityManager();
            foreach ($themePlaceMap->getPrices() as $price){
                $price->remove($themePlaceMap);
            }
            $em->remove($themePlaceMap);
            $em->flush();
        }

    }

    /**
     * @View()
     * @Put("/themePlaceMaps/{id}")
     */
    public function putThemePlaceMapAction(Request $request)
    {
        return $this->updateThemePlaceMap($request);
    }

    /**
     * @View()
     * @Patch("/themePlaceMaps/{id}")
     * @param Request $request
     */
    public function patchThemePlaceMapAction(Request $request){
        return $this->updateThemePlaceMap($request,false);
    }

    private function updateThemePlaceMap(Request $request,$clearMissing = true ){
        $themePlaceMap = $this->getDoctrine()->getRepository("MyBundle:ThemePlaceMap")->find($request->get('id'));
        if(empty($themePlaceMap)){
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(ThemePlaceMapType::class,$themePlaceMap);
        $form->submit($request->request->all(), $clearMissing);
        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($themePlaceMap);
            $em->flush();
            return $themePlaceMap;
        }else{
            return $form;
        }
    }
}