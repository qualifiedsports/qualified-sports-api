<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

/**
 * MedicalExamination
 *
 * @ORM\Table(name="medical_examination")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"medical_examination", "medical_examination-read"}},
 *     "denormalization_context"={"groups"={"medical_examination", "medical_examination-write"}}
 * })
 * @ApiFilter(DateFilter::class, properties={"at", "created"})
 */
class MedicalExamination
{
    const STATUS_EVALUATE = 'EVALUATE';
    const STATUS_DONE = 'DONE';
    const STATUS_NOT_DONE = 'NOT_DONE';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"medical_examination-read"})
     */
    private $id;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="medicalExaminations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"medical_examination"})
     */
    private $user;

    /**
     * @var \DateTime $at
     *
     * @ORM\Column(type="datetime")
     * @Groups({"medical_examination"})
     */
    private $at;

    /**
     * @var string
     *
     * @ORM\Column(name="examination_procedure", type="string")
     * @Groups({"medical_examination"})
     */
    private $procedure;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Groups({"medical_examination"})
     */
    private $description;

    /**
     * @var string
     *
     * @Groups({"medical_examination"})
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * @Groups({"medical_examination"})
     */
    private $createdBy;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"medical_examination-read"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"medical_examination-read"})
     */
    private $updated;

    /**
     * MedicalExamination constructor.
     */
    public function __construct()
    {
        $this->status = self::STATUS_EVALUATE;
    }

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
     * Set at.
     *
     * @param \DateTime $at
     *
     * @return MedicalExamination
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
     * Set procedure.
     *
     * @param string $procedure
     *
     * @return MedicalExamination
     */
    public function setProcedure($procedure)
    {
        $this->procedure = $procedure;

        return $this;
    }

    /**
     * Get procedure.
     *
     * @return string
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     * Set created.
     *
     * @param \DateTime|null $created
     *
     * @return MedicalExamination
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
     * @return MedicalExamination
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
     * @return MedicalExamination
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
     * @return MedicalExamination
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
     * Set description.
     *
     * @param string $description
     *
     * @return MedicalExamination
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return MedicalExamination
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
