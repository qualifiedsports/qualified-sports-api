<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"notification", "notification-read"}},
 *     "denormalization_context"={"groups"={"notification", "notification-write"}},
 *     "filters"={"notification.order_filter", "notification.search_filter", "notification.date_filter"}
 * })
 */
class Notification
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"notification", "user"})
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"notification"})
     * @ORM\Column(name="notification", type="text", nullable=true)
     */
    private $notification;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"notification"})
     */
    private $user;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"notification"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"notification-read"})
     */
    private $updated;

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
     * @param mixed $user
     *
     * @return Notification
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
     * @return Notification
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param \DateTime $updated
     *
     * @return Notification
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
     * Set notification.
     *
     * @param string $notification
     *
     * @return Notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification.
     *
     * @return string
     */
    public function getNotification()
    {
        return $this->notification;
    }
}
