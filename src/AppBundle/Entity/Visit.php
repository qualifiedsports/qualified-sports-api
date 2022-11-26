<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

/**
 * Visit
 *
 * @ORM\Table(name="visit")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"visit", "visit-read"}},
 *     "denormalization_context"={"groups"={"visit", "visit-write"}},
 *     "filters"={"visit.order_filter", "visit.search_filter", "visit.date_filter"}
 * }),
 * @ApiFilter(DateFilter::class, properties={"visitDate", "created"})
 */
class Visit
{
    const STATUS_EVALUATE = 'EVALUATE';
    const STATUS_DONE = 'DONE';
    const STATUS_NOT_DONE = 'NOT_DONE';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"visit", "user"})
     */
    private $id;

    /**
     * @var \DateTime $visitDate
     *
     * @Groups({"visit"})
     * @ORM\Column(type="datetime")
     */
    private $visitDate;

    /**
     * @var string
     *
     * @Groups({"visit"})
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @var string
     *
     * @Groups({"visit"})
     * @ORM\Column(name="doctor_fullname", type="string")
     */
    private $doctorFullname;

    /**
     * @var string
     *
     * @Groups({"visit"})
     * @ORM\Column(name="preparing_instructions", type="string")
     */
    private $preparingInstructions;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="visits")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"visit"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * @Groups({"visit"})
     */
    private $createdBy;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"visit"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"visit-read"})
     */
    private $updated;

    public function __construct()
    {
        $this->status = self::STATUS_EVALUATE;
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
     * Set visit date
     *
     * @param \DateTime $visitDate
     *
     * @return Visit
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visit date
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * @param mixed $user
     *
     * @return Visit
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
     * @return Visit
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param \DateTime $updated
     *
     * @return Visit
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
     * @return Visit
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
     * Set doctorFullname.
     *
     * @param string $doctorFullname
     *
     * @return Visit
     */
    public function setDoctorFullname($doctorFullname)
    {
        $this->doctorFullname = $doctorFullname;

        return $this;
    }

    /**
     * Get doctorFullname.
     *
     * @return string
     */
    public function getDoctorFullname()
    {
        return $this->doctorFullname;
    }

    /**
     * Set preparingInstructions.
     *
     * @param string $preparingInstructions
     *
     * @return Visit
     */
    public function setPreparingInstructions($preparingInstructions)
    {
        $this->preparingInstructions = $preparingInstructions;

        return $this;
    }

    /**
     * Get preparingInstructions.
     *
     * @return string
     */
    public function getPreparingInstructions()
    {
        return $this->preparingInstructions;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Visit
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
