<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * PsychoPhysicalDevelopment
 *
 * @ORM\Table(name="psycho_physical_development")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"psycho_physical_development", "psycho_physical_development-read"}},
 *     "denormalization_context"={"groups"={"psycho_physical_development", "psycho_physical_development-write"}},
 *     "filters"={"psycho_physical_development.order_filter", "psycho_physical_development.search_filter", "psycho_physical_development.date_filter"}
 * })
 */
class PsychoPhysicalDevelopment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"psycho_physical_development", "user"})
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"psycho_physical_development"})
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var \DateTime $at
     *
     * @ORM\Column(type="datetime")
     * @Groups({"psycho_physical_development"})
     */
    private $at;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="psychoPhysicalDevelopments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"psycho_physical_development"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * @Groups({"psycho_physical_development"})
     */
    private $createdBy;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"psycho_physical_development"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"psycho_physical_development-read"})
     */
    private $updated;

    public function __construct()
    {
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
     * Set value
     *
     * @param text $value
     *
     * @return PsychoPhysicalDevelopment
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return text
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $user
     *
     * @return PsychoPhysicalDevelopment
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \DateTime $created
     *
     * @return PsychoPhysicalDevelopment
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param \DateTime $updated
     *
     * @return PsychoPhysicalDevelopment
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set createdBy.
     *
     * @param \AppBundle\Entity\User|null $createdBy
     *
     * @return PsychoPhysicalDevelopment
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set at.
     *
     * @param \DateTime $at
     *
     * @return PsychoPhysicalDevelopment
     */
    public function setAt($at)
    {
        $this->at = $at;

        return $this;
    }

    /**
     * Get at.
     *
     * @return \DateTime
     */
    public function getAt()
    {
        return $this->at;
    }
}
