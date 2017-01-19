<?php

namespace MyBundle\Subscriber;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use FOS\UserBundle\Event\FormEvent;
use MyBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthSubscriber implements EventSubscriberInterface
{
    protected $doctrine;
    protected $user;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationSuccess',
        );
    }

    /*
    public function onRegistrationSuccess(){
        die('la');
    }

    */
    public function onRegistrationSuccess(FilterUserResponseEvent $event, $eventName, EventDispatcherInterface $eventDispatcher)
    {
        $rolesArr = array('ROLE_USER');


        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getUser();
        $user->addRole('ROLE_ADMIN');
        //$user->setRoles($rolesArr);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        /*
        $em = $this->doctrine->getEntityManager();
        $this->user = $event->getForm()->getData();
        $group_name = 'my_default_group_name';
        $entity = $this->em->getRepository('MoskitoUserBundle:Group')->findOneByName($group_name); // You could do that by Id, too
        $this->user->addGroup($entity);
        $this->em->flush();
*/
    }
}