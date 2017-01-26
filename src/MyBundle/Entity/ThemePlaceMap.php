<?php

namespace MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThemePlaceMap
 *
 * @ORM\Table(name="theme_place_map",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="uniqueTheme_Place",columns={"place_id","theme_id"})}
 *     )
 * @ORM\Entity(repositoryClass="MyBundle\Repository\ThemePlaceMapRepository")
 */
class ThemePlaceMap
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


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
     * @ORM\Column(name="value",type="integer")
     */
    private $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param mixed $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @ORM\ManyToOne(targetEntity="MyBundle\Entity\Place",inversedBy="themePlaceMap")
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity="MyBundle\Entity\Theme",inversedBy="themePlaceMap")
     */
    private $theme;
}

