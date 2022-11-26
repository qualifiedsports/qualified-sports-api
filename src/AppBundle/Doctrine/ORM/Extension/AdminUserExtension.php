<?php

namespace AppBundle\Doctrine\ORM\Extension;

use AppBundle\Entity\DietRecommendation;
use AppBundle\Entity\ImagingExamination;
use AppBundle\Entity\MedicalExamination;
use AppBundle\Entity\MedicalRecommendation;
use AppBundle\Entity\Recommendation;
use AppBundle\Entity\Consultation;
use AppBundle\Entity\Diagnostic;
use AppBundle\Entity\PsychoPhysicalDevelopment;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Training;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class AdminUserExtension
 *
 * @package AppBundle\Doctrine\ORM\Extension
 */
final class AdminUserExtension
{
    /**
     * @var array List of entities to be hydrated by actual admin context
     */
    private static $createdByHydratedModels = [
        ImagingExamination::class,
        Recommendation::class,
        Diagnostic::class,
        PsychoPhysicalDevelopment::class,
        Consultation::class,
        Visit::class,
        Training::class,
        MedicalExamination::class,
        MedicalRecommendation::class,
        DietRecommendation::class
    ];

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * CurrentUserExtension constructor.
     *
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationChecker $checker
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationChecker $checker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $checker;
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $token = $this->tokenStorage->getToken();
        $entity = $args->getEntity();

        $validInstance = in_array(get_class($entity), static::$createdByHydratedModels, true);

        if (null !== $token && $validInstance && !$this->authorizationChecker->isGranted('ROLE_PATIENT')) {
            $entity->setCreatedBy($token->getUser());
        }
    }
}
