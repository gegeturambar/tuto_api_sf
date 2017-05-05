<?php

namespace MyBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Put;
use MyBundle\Form\HeroType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MyBundle\Entity\Hero;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;

class HeroController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getHeroesAction(Request $request)
    {

        // check first in request if there are anything
        $name = $request->get('name');

        if($name){
            $heroes = $this->get('doctrine.orm.entity_manager')
                ->getRepository('MyBundle:Hero')
                ->findByName($name,1);
        }else {
            $heroes = $this->get('doctrine.orm.entity_manager')
                ->getRepository('MyBundle:Hero')
                ->findAll();
        }
        /* @var $heroes Hero[] */

        return $heroes;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getHeroAction($id, Request $request)
    {
        $hero = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:Hero')
            ->find($id);
        /* @var $hero Hero */
        if (empty($hero)) {
            throw $this->createNotFoundException();
        }
        return $hero;
    }

    /**
     * @View(statusCode=Response::HTTP_CREATED)
     * @Post("/heroes")
     */
    public function postHeroesAction(Request $request)
    {
        $hero = new Hero();
        /*
        $hero->setName($request->get('name'))
            ->setAddress($request->get('address'));
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($hero);
        $em->flush();
        return $hero;
        */
        $form = $this->createForm(HeroType::class,$hero);

        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($hero);
            $em->flush();
            return $hero;
        }else{
            return $form;
        }

    }

    /**
     * @View(statusCode=Response::HTTP_NO_CONTENT)
     * @Delete("/heroes/{id}")
     * @param Request $request
     */
    public function deleteHeroAction(Request $request){
        $hero = $this->getDoctrine()->getRepository("MyBundle:Hero")->find($request->get('id'));
        /* @var $hero Hero */
        if($hero) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($hero);
            $em->flush();
        }

    }

    /**
     * @View()
     * @Put("/heroes/{id}")
     */
    public function putHeroAction(Request $request)
    {
        return $this->updateHero($request);
    }

    /**
     * @View()
     * @Patch("/heroes/{id}")
     * @param Request $request
     */
    public function patchHeroAction(Request $request){
        return $this->updateHero($request,false);
    }

    private function updateHero(Request $request,$clearMissing = true ){
        $hero = $this->getDoctrine()->getRepository("MyBundle:Hero")->find($request->get('id'));
        if(empty($hero)){
            throw $this->createNotFoundException();
        }

//TODO  add gege cause with it it fails, check with forum best way to do so
        $request->request->remove('id');
        $form = $this->createForm(HeroType::class,$hero);
        $form->submit($request->request->all(), $clearMissing);

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($hero);
            $em->flush();
            return $hero;
        }else{
            return $form;
        }
    }
}