<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DietRecommendation
 *
 * @ORM\Table(name="diet_recommendation")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"diet_recommendation", "diet_recommendation-read"}},
 *     "denormalization_context"={"groups"={"diet_recommendation", "diet_recommendation-write"}},
 *     "filters"={"diet_recommendation.order_filter", "diet_recommendation.search_filter", "diet_recommendation.date_filter"}
 * })
 */
class DietRecommendation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"diet_recommendation-read", "user-read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="diet_recommendations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"diet_recommendation"})
     */
    private $user;

    /**
     * @var string
     *
     * @Groups({"diet_recommendation"})
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var \DateTime $at
     *
     * @ORM\Column(type="datetime")
     * @Groups({"diet_recommendation"})
     */
    private $at;

    /**
     * @var \DateTime $ends
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"diet_recommendation"})
     */
    private $ends;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MediaObject")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"diet_recommendation"})
     */
    private $attachment;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * @Groups({"diet_recommendation"})
     */
    private $createdBy;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"diet_recommendation"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"diet_recommendation-read"})
     */
    private $updated;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return DietRecommendation
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set at.
     *
     * @param \DateTime $at
     *
     * @return DietRecommendation
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

    /**
     * Set ends.
     *
     * @param \DateTime|null $ends
     *
     * @return DietRecommendation
     */
    public function setEnds($ends = null)
    {
        $this->ends = $ends;

        return $this;
    }

    /**
     * Get ends.
     *
     * @return \DateTime|null
     */
    public function getEnds()
    {
        return $this->ends;
    }

    /**
     * Set created.
     *
     * @param \DateTime|null $created
     *
     * @return DietRecommendation
     */
    public function setCreated($created = null)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime|null
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTime|null $updated
     *
     * @return DietRecommendation
     */
    public function setUpdated($updated = null)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime|null
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return DietRecommendation
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set createdBy.
     *
     * @param \AppBundle\Entity\User|null $createdBy
     *
     * @return DietRecommendation
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
     * @return DietRecommendation
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
