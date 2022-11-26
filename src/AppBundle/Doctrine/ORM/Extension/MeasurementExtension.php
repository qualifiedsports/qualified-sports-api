<?php

namespace AppBundle\Doctrine\ORM\Extension;

use AppBundle\Entity\Measurement;
use AppBundle\Entity\NotificationFactory;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class MeasurementExtension
 *
 * @package AppBundle\Doctrine\ORM\Extension
 */
final class MeasurementExtension
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
        $entityManager = $args->getObjectManager();

        /** @var $entity Measurement */
        if (null !== $token && $entity instanceof Measurement) {
            $notification = null;
            switch ($entity->getType()->getId()) {
                case 1:  // Ciśnienie tętnicze
                    list($systolic, $diastolic) = explode('/', $entity->getValue());
                    $systolic = (int) $systolic;
                    $diastolic = (int) $diastolic;
                    if ($systolic > 150 || $diastolic > 90) {
                        $notification = NotificationFactory::measurementOfBloodPressureAnomalyDetected($token->getUser(), $systolic, $diastolic);
                    }
                    break;
                case 8:  // Tętno spoczynkowe
                    if ($entity->getValue() > 100 || $entity->getValue() < 50) {
                        $notification = NotificationFactory::measurementOfHeartRateAnomalyDetected($token->getUser(), $entity->getValue());
                    }
                    break;
                case 9:  // Glukoza na czczo
                    if ($entity->getValue() > 100 || $entity->getValue() < 60) {
                        $notification = NotificationFactory::measurementOfFastingGlucoseAnomalyDetected($token->getUser(), $entity->getValue());
                    }
                    break;
                case 10: // Saturacja Sp02
                    if ($entity->getValue() < 96) {
                        $notification = NotificationFactory::measurementOfRespiratoryAnomalyDetected($token->getUser(), $entity->getValue());
                    }
                    break;
                default:
                    break;
            }

            if ($notification !== null) {
                /** @var $user User */
                $user = $token->getUser();
                foreach ($user->getDoctors() as $doctor) {
                    $doctorNotification = NotificationFactory::measurementAnomalyDetected($doctor, $user->getFullname(), $entity->getType()->getName(), $entity->getValue());
                    $entityManager->persist($doctorNotification);
                    $entityManager->flush();
                }

                $entityManager->persist($notification);
                $entityManager->flush();    
            }
        }
    }
}
