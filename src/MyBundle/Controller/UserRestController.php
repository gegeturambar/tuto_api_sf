<?php

namespace MyBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserRestController extends Controller
{
    public function getUserAction($username){
        $user = $this->getDoctrine()->getRepository('MyBundle:User')->findOneByUsername($username);
        if(!is_object($user)){
            throw $this->createNotFoundException();
        }
        return $user;
    }

}
