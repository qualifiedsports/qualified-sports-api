<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * RecommendationType
 *
 * @ORM\Table(name="recommendation_type")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"recommendation_type", "recommendation_type-read"}},
 *     "denormalization_context"={"groups"={"recommendation_type", "recommendation_type-write"}},
 * })
 */

class RecommendationType
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"recommendation_type"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Groups({"recommendation_type"})
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Recommendation", mappedBy="type")
     */
    private $recommendations;

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
     * @return MeasurementType
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
     * Constructor
     */
    public function __construct()
    {
        $this->recommendations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add recommendation
     *
     * @param \AppBundle\Entity\Recommendation $recommendation
     *
     * @return MeasurementType
     */
    public function addRecommendation(\AppBundle\Entity\Recommendation $recommendation)
    {
        $this->recommendations[] = $recommendation;

        return $this;
    }

    /**
     * Remove recommendation
     *
     * @param \AppBundle\Entity\Recommendation $recommendation
     */
    public function removeRecommendation(\AppBundle\Entity\Recommendation $recommendation)
    {
        $this->recommendations->removeElement($recommendation);
    }

    /**
     * Get recommendations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecommendations()
    {
        return $this->recommendations;
    }
}
