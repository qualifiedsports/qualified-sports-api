<?php

namespace AppBundle\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use AppBundle\Entity\MediaObject;
use AppBundle\Form\MediaObjectType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class MediaObjectNew
 *
 * @package AppBundle\Controller
 */
final class MediaObjectNew
{
    /**
     * @var \Vich\UploaderBundle\Templating\Helper\UploaderHelper
     */
    private $helper;
    /**
     * @var \Symfony\Bridge\Doctrine\RegistryInterface
     */
    private $doctrine;
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $factory;
    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * MediaObjectNew constructor.
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface                $doctrine
     * @param \Symfony\Component\Form\FormFactoryInterface              $factory
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param \Vich\UploaderBundle\Templating\Helper\UploaderHelper     $helper
     */
    public function __construct(
        RegistryInterface $doctrine,
        FormFactoryInterface $factory,
        ValidatorInterface $validator,
        UploaderHelper $helper
    )
    {
        $this->helper = $helper;
        $this->doctrine = $doctrine;
        $this->factory = $factory;
        $this->validator = $validator;
    }

    /**
     * @Route(
     *     name="api_media_objects_upload",
     *     path="/api/media_objects",
     *     methods={"POST"},
     *     defaults={"_api_resource_class"=MediaObject::class, "_api_item_operation_name"="upload",
     *     "_api_receive"=false}
     * )
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return MediaObject
     */
    public function __invoke(Request $request): MediaObject
    {
        $mediaObject = new MediaObject();

        $form = $this->factory->create(MediaObjectType::class, $mediaObject);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($mediaObject);
            $em->flush();

            // Prevent the serialization of the file property
            $mediaObject->file = null;

            return $mediaObject;
        }

        // This will be handled by API Platform and returns a validation error.
        throw new ValidationException($this->validator->validate($mediaObject));
    }
}