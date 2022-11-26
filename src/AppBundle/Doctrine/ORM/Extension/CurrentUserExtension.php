<?php

namespace AppBundle\Doctrine\ORM\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use AppBundle\Entity\DietRecommendation;
use AppBundle\Entity\Measurement;
use AppBundle\Entity\MedicalExamination;
use AppBundle\Entity\MedicalRecommendation;
use AppBundle\Entity\Recommendation;
use AppBundle\Entity\Diagnostic;
use AppBundle\Entity\PsychoPhysicalDevelopment;
use AppBundle\Entity\Consultation;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Training;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class CurrentUserExtension
 *
 * @package AppBundle\Doctrine\ORM\Extension
 */
final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * List of classes which are extended by current user context.
     *
     * @var array
     */
    private static $currentUserModels = [
        Measurement::class,
        Consultation::class,
        PsychoPhysicalDevelopment::class,
        Visit::class,
        Diagnostic::class,
        Training::class,
        MedicalExamination::class,
        MedicalRecommendation::class,
        Notification::class,
        DietRecommendation::class
    ];

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

        $validInstance = $entity instanceof Measurement || $entity instanceof Recommendation || $entity instanceof Diagnostic || $entity instanceof Consultation || $entity instanceof Visit || $entity instanceof Training;

        if (null !== $token && $validInstance && $this->authorizationChecker->isGranted('ROLE_PATIENT')) {
            $entity->setUser($token->getUser());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     * {@inheritdoc}
     */
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     *
     * @param QueryBuilder $queryBuilder
     * @param string $resourceClass
     */
    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof User && !$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $rootAlias = $queryBuilder->getRootAliases()[0];

            if ($this->authorizationChecker->isGranted('ROLE_DOCTOR')) {
                if ($resourceClass === User::class) {
                    $queryBuilder->innerJoin(sprintf('%s.doctors', $rootAlias), 'd')->andWhere($queryBuilder->expr()->eq('d.id', $user->getId()));
                }

                if ($resourceClass !== Notification::class && in_array($resourceClass, $this::$currentUserModels)) {
                    $queryBuilder->join(sprintf('%s.user', $rootAlias), 'u');
                    $queryBuilder->innerJoin(sprintf('u.doctors', $rootAlias), 'd')->andWhere($queryBuilder->expr()->eq('d.id', $user->getId()));
                }

                if ($resourceClass === Notification::class) {
                    $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
                    $queryBuilder->setParameter('current_user', $user->getId());
                }
            }

            if ($this->authorizationChecker->isGranted('ROLE_PATIENT')) {
                if ($resourceClass === User::class) {
                    $queryBuilder->innerJoin(sprintf('%s.patients', $rootAlias), 'p')->andWhere($queryBuilder->expr()->eq('p.id', $user->getId()));
                }

                if (in_array($resourceClass, $this::$currentUserModels)) {
                    $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
                    $queryBuilder->setParameter('current_user', $user->getId());
                }
            }
        }
    }
}
