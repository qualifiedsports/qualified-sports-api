<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Thread as BaseThread;
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="fos_thread")
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"thread", "thread-read"}},
 *     "denormalization_context"={"groups"={"thread", "thread-write"}}
 * }, itemOperations={
 *     "my"={"route_name"="threads_my"},
 *     "my_single"={"route_name"="threads_my_single"},
 *     "new"={"route_name"="threads_new"},
 *     "my_dashboard"={"route_name"="threads_my_dashboard"}
 * })
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"thread-read"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     * @Groups({"thread-read"})
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="AppBundle\Entity\Message",
     *   mappedBy="thread"
     * )
     * @var Message[]|Collection
     * @Groups({"thread-read"})
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="AppBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @var ThreadMetadata[]|Collection
     * @Groups({"thread-read"})
     */
    protected $metadata;

    /**
     * @Groups({"thread-read"})
     */
    protected $subject;

    /**
     * @Groups({"thread-read"})
     */
    protected $createdAt;

    /**
     * @Groups({"thread-read"})
     */
    private $hasBeenRead;

    /**
     * Remove message.
     *
     * @param \AppBundle\Entity\Message $message
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMessage(\AppBundle\Entity\Message $message)
    {
        return $this->messages->removeElement($message);
    }

    /**
     * Add metadata.
     *
     * @param \AppBundle\Entity\ThreadMetadata $metadata
     *
     * @return Thread
     */
    public function addMetadatum(\AppBundle\Entity\ThreadMetadata $metadata)
    {
        $this->metadata[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata.
     *
     * @param \AppBundle\Entity\ThreadMetadata $metadata
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMetadatum(\AppBundle\Entity\ThreadMetadata $metadata)
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

    public function isReadByParticipant(ParticipantInterface $participant)
    {
        return $this->hasBeenRead = parent::isReadByParticipant($participant);
    }

    /**
     * @return mixed
     */
    public function getHasBeenRead()
    {
        return $this->hasBeenRead;
    }
}
