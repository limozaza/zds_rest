<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * Theme
 *
 * @ORM\Table(name="themes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ThemeRepository")
 */
class Theme
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"theme","place"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotNull()
     * @Assert\Choice(
     *     choices={"art","architecture","history","science-fiction","sport"}
     * )
     *
     * @Serializer\Groups({"theme","place"})
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     *
     * @Assert\NotNull()
     * @Assert\Type("numeric")
     * @Assert\GreaterThan(
     *     value=0
     * )
     * @Assert\LessThanOrEqual(
     *     value=10
     * )
     *
     * @Serializer\Groups({"theme","place"})
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Place", inversedBy="themes")
     *
     * @Serializer\Groups({"theme"})
     */
    private $place;


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
     * @return Theme
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
     * Set value
     *
     * @param integer $value
     *
     * @return Theme
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set place
     *
     * @param \AppBundle\Entity\Place $place
     *
     * @return Theme
     */
    public function setPlace(\AppBundle\Entity\Place $place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return \AppBundle\Entity\Place
     */
    public function getPlace()
    {
        return $this->place;
    }
}
