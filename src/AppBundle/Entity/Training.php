<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;


/**
 * Training
 *
 * @ORM\Table(name="training")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"training", "training-read"}},
 *     "denormalization_context"={"groups"={"training", "training-write"}},
 * })
 * @ApiFilter(DateFilter::class, properties={"at", "ends", "created"})
 */
class Training
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
     * @Groups({"training", "user"})
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"training"})
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var string
     *
     * @Groups({"training"})
     * @ORM\Column(name="result", type="text")
     */
    private $result;

    /**
     * @var string
     *
     * @Groups({"training"})
     * @ORM\Column(name="resultAchieved", type="text")
     */
    private $resultAchieved;

    /**
     * @var \DateTime
     *
     * @Groups({"training"})
     * @ORM\Column(name="at", type="date")
     */
    private $at;

    /**
     * @var \DateTime
     *
     * @Groups({"training"})
     * @ORM\Column(name="ends", type="date")
     */
    private $ends;

    /**
     * @var string
     *
     * @Groups({"training"})
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="trainings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"training"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * @Groups({"training"})
     */
    private $createdBy;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"training"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"training-read"})
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MediaObject")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"training"})
     */
    private $attachment;

    /**
     * Training constructor.
     * @param int $id
     */
    public function __construct()
    {
        $this->status = self::STATUS_EVALUATE;
        $this->result = '';
        $this->resultAchieved = '';
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
     * @return Training
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
     * @return Training
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
     * @return Training
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param \DateTime $updated
     *
     * @return Training
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
     * @return Training
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
     * Set ends
     *
     * @param \DateTime $ends
     *
     * @return Training
     */
    public function setEnds($ends = null)
    {
        $this->ends = $ends;

        return $this;
    }

    /**
     * Get ends
     *
     * @return \DateTime
     */
    public function getEnds()
    {
        return $this->ends;
    }

    /**
     * Set createdBy.
     *
     * @param \AppBundle\Entity\User|null $createdBy
     *
     * @return Training
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
     * @return Training
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

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Training
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

    /**
     * Set result.
     *
     * @param string $result
     *
     * @return Training
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result.
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set resultAchieved.
     *
     * @param string $resultAchieved
     *
     * @return Training
     */
    public function setResultAchieved($resultAchieved)
    {
        $this->resultAchieved = $resultAchieved;

        return $this;
    }

    /**
     * Get resultAchieved.
     *
     * @return string
     */
    public function getResultAchieved()
    {
        return $this->resultAchieved;
    }
}
