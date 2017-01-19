<?php

namespace MyBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UsersController extends FOSRestController
{
    /**
     * @Route("/getUsers")
     */
    public function getUsersAction()
    {
        $data = $this->getDoctrine()->getRepository('MyBundle:User');
        $view =   $this->view($data,200)->setTemplate('MyBundle:Users:get_users.html.twig')->setTemplateVar('users');
        return $this->handleView($view);
    }

    /**
     * @Route("/redirect")
     */
    public function redirectAction()
    {
        $view = $this->redirectView($this->generateUrl(''),301);
        return $this->handleView($view);

    }

}
