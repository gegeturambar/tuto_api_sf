<?php

namespace MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Place
 *
 * @ORM\Table(name="places")
 * @ORM\Entity(repositoryClass="MyBundle\Repository\PlaceRepository")
 * @UniqueEntity("name")
 *
 */
class Place
{
    public function __construct($name,$address)
    {
        $this->address = $address;
        $this->name = $name;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @return mixed
     */
    public function getThemePlaceMap()
    {
        return $this->themePlaceMap;
    }

    /**
     * @param mixed $themePlaceMap
     */
    public function setThemePlaceMap($themePlaceMap)
    {
        $this->themePlaceMap = $themePlaceMap;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="MyBundle\Entity\ThemePlaceMap",mappedBy="place")
     */
    private $themePlaceMap;

    /**
     * @ORM\OneToMany(targetEntity="MyBundle\Entity\Price", mappedBy="place")
     * @var Price[]
     */
    private $prices;

    /**
     * @return Price[]
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param Price[] $prices
     */
    public function setPrices($prices)
    {
        $this->prices = $prices;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Place
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}

