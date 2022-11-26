<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Recommendation
 *
 * @ORM\Table(name="recommendation")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"recommendation", "recommendation-read"}},
 *     "denormalization_context"={"groups"={"recommendation", "recommendation-write"}},
 *     "filters"={"recommendation.order_filter", "recommendation.search_filter", "recommendation.date_filter"}
 * })
 */
class Recommendation
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"recommendation", "user"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RecommendationType", inversedBy="recommendations")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * @Groups({"recommendation"})
     */
    private $type;

    /**
     * @var string
     *
     * @Groups({"recommendation"})
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @Groups({"recommendation"})
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", cascade={"persist"}, inversedBy="recommendations")
     * @ORM\JoinTable(name="fos_user_recommendations",
     *     joinColumns={@ORM\JoinColumn(name="recommendation_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $users;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * @Groups({"recommendation"})
     */
    private $createdBy;

    /**
     * @var \DateTime $at
     *
     * @ORM\Column(type="datetime")
     * @Groups({"recommendation"})
     */
    private $at;

    /**
     * @var \DateTime $ends
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"recommendation"})
     */
    private $ends;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"recommendation"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"recommendation-read"})
     */
    private $updated;

    /**
     * Recommendation constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return Recommendation
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
     * @param \DateTime $created
     *
     * @return Recommendation
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param \DateTime $updated
     *
     * @return Recommendation
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
     * Set at
     *
     * @param \DateTime $at
     *
     * @return Recommendation
     */
    public function setAt($at)
    {
        $this->at = $at;

        return $this;
    }

    /**
     * Get at
     *
     * @return \DateTime
     */
    public function getAt()
    {
        return $this->at;
    }

    /**
     * Set at
     *
     * @param \DateTime $ends
     *
     * @return Recommendation
     */
    public function setEnds($ends = null)
    {
        $this->ends = $ends;

        return $this;
    }

    /**
     * Get at
     *
     * @return \DateTime
     */
    public function getEnds()
    {
        return $this->ends;
    }

    /**
     * Set type
     *
     * @param \AppBundle\Entity\RecommendationType $type
     *
     * @return Recommendation
     */
    public function setType(\AppBundle\Entity\RecommendationType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\RecommendationType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set createdBy.
     *
     * @param \AppBundle\Entity\User|null $createdBy
     *
     * @return Recommendation
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
     * Add user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Recommendation
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
