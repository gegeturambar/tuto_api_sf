<?php

namespace MyBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Put;
use MyBundle\Form\PreferenceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MyBundle\Entity\Preference;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;

class PreferenceController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPreferencesAction(Request $request)
    {
        $preferences = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:Preference')
            ->findAll();
        /* @var $preferences Preference[] */

        return $preferences;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPreferenceAction($id, Request $request)
    {
        $preference = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MyBundle:Preference')
            ->find($id);
        /* @var $preference Preference */
        if (empty($preference)) {
            throw $this->createNotFoundException();
        }
        return $preference;
    }

    /**
     * @View(statusCode=Response::HTTP_CREATED)
     * @Post("/preferences")
     */
    public function postPreferencesAction(Request $request)
    {
        $preference = new Preference();

        $form = $this->createForm(PreferenceType::class,$preference);

        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($preference);
            $em->flush();
            return $preference;
        }else{
            return $form;
        }

    }

    /**
     * @View(statusCode=Response::HTTP_NO_CONTENT)
     * @Delete("/preferences/{id}")
     * @param Request $request
     */
    public function deletePreferenceAction(Request $request){
        $preference = $this->getDoctrine()->getRepository("MyBundle:Preference")->find($request->get('id'));
        /* @var $preference Preference */
        if($preference) {
            $em = $this->getDoctrine()->getEntityManager();
            foreach ($preference->getPrices() as $price){
                $price->remove($preference);
            }
            $em->remove($preference);
            $em->flush();
        }

    }

    /**
     * @View()
     * @Put("/preferences/{id}")
     */
    public function putPreferenceAction(Request $request)
    {
        return $this->updatePreference($request);
    }

    /**
     * @View()
     * @Patch("/preferences/{id}")
     * @param Request $request
     */
    public function patchPreferenceAction(Request $request){
        return $this->updatePreference($request,false);
    }

    private function updatePreference(Request $request,$clearMissing = true ){
        $preference = $this->getDoctrine()->getRepository("MyBundle:Preference")->find($request->get('id'));
        if(empty($preference)){
            throw $this->createNotFoundException("preference not found");
        }
        $form = $this->createForm(PreferenceType::class,$preference);
        $form->submit($request->request->all(), $clearMissing);
        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($preference);
            $em->flush();
            return $preference;
        }else{
            return $form;
        }
    }
}