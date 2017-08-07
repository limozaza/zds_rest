<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * Preference
 *
 * @ORM\Table(name="preferences")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PreferenceRepository")
 */
class Preference
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"user","preference"})
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
     * @Serializer\Groups({"user","preference"})
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
     * @Serializer\Groups({"user","preference"})
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="preferences")
     *
     * @Serializer\Groups({"preference"})
     */
    private $user;


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
     * @return Preference
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
     * @return Preference
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Preference
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
