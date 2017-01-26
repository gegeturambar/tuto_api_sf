<?php

namespace MyBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Put;
use MyBundle\Form\ThemeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MyBundle\Entity\Theme;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;

class ThemeController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getThemesAction(Request $request)
    {
        $themes = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:Theme')
            ->findAll();
        /* @var $themes Theme[] */

        return $themes;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getThemeAction($id, Request $request)
    {
        $theme = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:Theme')
            ->find($id);
        /* @var $theme Theme */
        if (empty($theme)) {
            throw $this->createNotFoundException();
        }
        return $theme;
    }

    /**
     * @View(statusCode=Response::HTTP_CREATED)
     * @Post("/themes")
     */
    public function postThemesAction(Request $request)
    {
        $theme = new Theme();

        $form = $this->createForm(ThemeType::class,$theme);

        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($theme);
            $em->flush();
            return $theme;
        }else{
            return $form;
        }

    }

    /**
     * @View(statusCode=Response::HTTP_NO_CONTENT)
     * @Delete("/themes/{id}")
     * @param Request $request
     */
    public function deleteThemeAction(Request $request){
        $theme = $this->getDoctrine()->getRepository("MyBundle:Theme")->find($request->get('id'));
        /* @var $theme Theme */
        if($theme) {
            $em = $this->getDoctrine()->getEntityManager();
            foreach ($theme->getPrices() as $price){
                $price->remove($theme);
            }
            $em->remove($theme);
            $em->flush();
        }

    }

    /**
     * @View()
     * @Put("/themes/{id}")
     */
    public function putThemeAction(Request $request)
    {
        return $this->updateTheme($request);
    }

    /**
     * @View()
     * @Patch("/themes/{id}")
     * @param Request $request
     */
    public function patchThemeAction(Request $request){
        return $this->updateTheme($request,false);
    }

    private function updateTheme(Request $request,$clearMissing = true ){
        $theme = $this->getDoctrine()->getRepository("MyBundle:Theme")->find($request->get('id'));
        if(empty($theme)){
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(ThemeType::class,$theme);
        $form->submit($request->request->all(), $clearMissing);
        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($theme);
            $em->flush();
            return $theme;
        }else{
            return $form;
        }
    }
}