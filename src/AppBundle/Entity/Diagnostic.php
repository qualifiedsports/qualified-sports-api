<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Diagnostic
 *
 * @ORM\Table(name="diagnostic")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"diagnostic", "diagnostic-read"}},
 *     "denormalization_context"={"groups"={"diagnostic", "diagnostic-write"}},
 *     "filters"={"diagnostic.order_filter", "diagnostic.search_filter", "diagnostic.date_filter"}
 * })
 */
class Diagnostic
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"diagnostic", "user"})
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"diagnostic"})
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="diagnostics")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"diagnostic"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * @Groups({"diagnostic"})
     */
    private $createdBy;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"diagnostic"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"diagnostic-read"})
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MediaObject")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"diagnostic"})
     */
    private $attachment;

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
     * @return Diagnostic
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
     * @return Diagnostic
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
     * @return Diagnostic
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param \DateTime $updated
     *
     * @return Diagnostic
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
     * @return Diagnostic
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
     * Set attachment.
     *
     * @param \AppBundle\Entity\MediaObject|null $attachment
     *
     * @return Diagnostic
     */
    public function setAttachment(\AppBundle\Entity\MediaObject $attachment = null)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * Get attachment.
     *
     * @return \AppBundle\Entity\MediaObject|null
     */
    public function getAttachment()
    {
        return $this->attachment;
    }
}
