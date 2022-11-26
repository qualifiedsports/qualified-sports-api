<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/MediaObject",
 *     collectionOperations={
 *          "get",
 *          "upload"={"route_name"="api_media_objects_upload"}
 *     },
 *     attributes={
 *          "normalization_context"={"groups"={"media_object", "media_object-read"}},
 *          "denormalization_context"={"groups"={"media_object", "media_object-write"}}
 *      })
 * @Vich\Uploadable
 */
class MediaObject
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"media_object-read", "recommendation-read", "message-read", "thread-read", "imaging_examination-read", "consultation-read", "training-read", "diagnostic-read", "medical_recommendation-read", "diet_recommendation-read"})
     */
    protected $id;

    /**
     * @var File|null
     * @Assert\NotNull()
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="contentUrl", originalName="name")
     */
    public $file;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"media_object-read", "recommendation-read", "message-read", "thread-read", "imaging_examination-read", "consultation-read", "training-read", "diagnostic-read", "medical_recommendation-read", "diet_recommendation-read"})
     */
    private $contentUrl;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @ApiProperty(iri="http://schema.org/name")
     * @Groups({"media_object-read", "recommendation-read", "message-read", "thread-read", "imaging_examination-read", "consultation-read", "training-read", "diagnostic-read", "medical_recommendation-read", "diet_recommendation-read"})
     */
    private $name;

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
     * Set contentUrl.
     *
     * @param string|null $contentUrl
     *
     * @return MediaObject
     */
    public function setContentUrl($contentUrl = null)
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    /**
     * Get contentUrl.
     *
     * @return string|null
     */
    public function getContentUrl()
    {
        return $this->contentUrl;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return MediaObject
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
}
