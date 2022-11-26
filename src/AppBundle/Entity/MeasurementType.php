<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * MeasurementType
 *
 * @ORM\Table(name="measurement_type")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"measurement_type", "measurement_type-read"}},
 *     "denormalization_context"={"groups"={"measurement_type", "measurement_type-write"}}
 * })
 */
class MeasurementType
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"measurement_type"})
     */
    private $id;

    /**
     * @var string

     * @ORM\Column(name="name", type="string", length=255)
     * @Groups({"measurement_type"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=255)
     * @Groups({"measurement_type"})
     */
    private $unit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Measurement", mappedBy="type")
     */
    private $measurements;

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
     * Set unit
     *
     * @param string $unit
     *
     * @return MeasurementType
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->measurements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add measurement
     *
     * @param \AppBundle\Entity\Measurement $measurement
     *
     * @return MeasurementType
     */
    public function addMeasurement(\AppBundle\Entity\Measurement $measurement)
    {
        $this->measurements[] = $measurement;

        return $this;
    }

    /**
     * Remove measurement
     *
     * @param \AppBundle\Entity\Measurement $measurement
     */
    public function removeMeasurement(\AppBundle\Entity\Measurement $measurement)
    {
        $this->measurements->removeElement($measurement);
    }

    /**
     * Get measurements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeasurements()
    {
        return $this->measurements;
    }
}
