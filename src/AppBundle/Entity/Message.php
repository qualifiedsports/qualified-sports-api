<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Message as BaseMessage;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="fos_message")
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"message", "message-read"}},
 *     "denormalization_context"={"groups"={"message", "message-write"}},
 *     "filters"={"message.search_filter"},
 *     "order"={"createdAt": "ASC"}
 * })
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"message-read"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="AppBundle\Entity\Thread",
     *      inversedBy="messages",
     *      cascade={"persist"}
     * )
     * @var \FOS\MessageBundle\Model\ThreadInterface
     * @Groups({"message-write"})
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     * @Groups({"message-read", "thread-read"})
     */
    protected $sender;

    /**
     * @ORM\OneToMany(
     *   targetEntity="AppBundle\Entity\MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * @var MessageMetadata[]|Collection
     */
    protected $metadata;

    /**
     * @Groups({"message-read", "thread-read"})
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MediaObject")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"message", "thread-read"})
     */
    private $attachment;

    /**
     * @Groups({"message-read", "thread-read"})
     */
    protected $createdAt;

    /**
     * Add metadata.
     *
     * @param \AppBundle\Entity\MessageMetadata $metadata
     *
     * @return Message
     */
    public function addMetadatum(\AppBundle\Entity\MessageMetadata $metadata)
    {
        $this->metadata[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata.
     *
     * @param \AppBundle\Entity\MessageMetadata $metadata
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMetadatum(\AppBundle\Entity\MessageMetadata $metadata)
    {
        return $this->metadata->removeElement($metadata);
    }

    /**
     * Get metadata.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set attachment.
     *
     * @param \AppBundle\Entity\MediaObject|null $attachment
     *
     * @return Message
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
