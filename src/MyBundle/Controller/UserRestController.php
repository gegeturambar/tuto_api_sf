<?php

namespace MyBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use MyBundle\Entity\User;
use MyBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;


class UserRestController extends Controller
{
    /**
     *
     * @param string $username
     *
     * @View(serializerGroups={"Default","Details"})
     */
    public function getUserAction($username){
        if(!$username)
            die('no username');
        $user = $this->getDoctrine()->getRepository('MyBundle:User')->findOneByUsername($username);
        if(!is_object($user)){
            throw $this->createNotFoundException();
        }
        return $user;
    }

    /**
     * @return array|\MyBundle\Entity\User[]
     *
     * @View(serializerGroups={"Default","Me","Details"})
     */
    public function getUsersAction(){
        $users = $this->getDoctrine()->getRepository('MyBundle:User')->findAll();
        if(!count($users)){
            throw $this->createNotFoundException();
        }
        return $users;
    }

    /**
     * @Post("/users/{type}/{typeId}")
     * */
    public function postUsersAction(Request $request){
        return $request->get('username');
    }

    public function putUsersAction(Request $request,$id){
        $user = $this->getDoctrine()->getRepository('MyBundle:User')->find($id);
        $form = $this->createForm(new RegistrationType(), $user, array('method' => 'PUT'));
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->view(null, Codes::HTTP_NO_CONTENT);
        }

        return array(
            'form' => $form,
        );
    }

    public function newUsersAction($data){
        var_dump($data);
        die('HERE, it all ');
    }

    public function copyUserAction(Request $request, $id){
        $user = $this->getDoctrine()->getRepository('MyBundle:User')->find($id);
        $em = $this->getDoctrine()->getEntityManager(User::class);
        $em->copy($user);

    }

    /**
     *
     * @View(serializerGroups={"Default","Me","Details"})
     */
    public function getMeAction(){
        $this->forwardIfNotAuthenticated();
        return $this->getUser();
    }

    /**
     * Shortcut to throw a AccessDeniedException($message) if the user is not authenticated
     * @param String $message The message to display (default:'warn.user.notAuthenticated')
     */
    protected function forwardIfNotAuthenticated($message='warn.user.notAuthenticated'){
        if (!is_object($this->getUser()))
        {
            throw new AccessDeniedException($message);
        }
    }

}
