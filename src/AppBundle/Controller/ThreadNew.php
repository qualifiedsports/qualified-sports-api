<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Thread;
use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidParameterException;

/**
 * Class MessageSend
 *
 * @package AppBundle\Controller
 */
class ThreadNew extends Controller
{
    /**
     * @var \FOS\MessageBundle\Composer\ComposerInterface
     */
    private $composer;
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var \FOS\MessageBundle\Provider\ProviderInterface
     */
    private $provider;
    /**
     * @var \FOS\MessageBundle\Sender\SenderInterface
     */
    private $sender;

    /**
     * MessageSend constructor.
     *
     * @param \FOS\MessageBundle\Composer\ComposerInterface $composer
     * @param \FOS\MessageBundle\Provider\ProviderInterface $provider
     * @param \Doctrine\ORM\EntityManagerInterface          $entityManager
     * @param \FOS\MessageBundle\Sender\SenderInterface     $sender
     */
    public function __construct(
        ComposerInterface $composer,
        ProviderInterface $provider,
        EntityManagerInterface $entityManager,
        SenderInterface $sender
    ) {
        $this->composer = $composer;
        $this->provider = $provider;
        $this->entityManager = $entityManager;
        $this->sender = $sender;
    }

    /**
     * @Route(
     *     name="threads_new",
     *     path="/api/threads/my",
     *     methods={"POST"},
     *     defaults={"_api_resource_class"=Thread::class, "_api_item_operation_name"="my", "_api_receive"=false}
     * )
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \FOS\MessageBundle\Model\ThreadInterface
     */
    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!array_key_exists('user', $data)) {
            throw new InvalidParameterException('No user is defined');
        }

        if (!array_key_exists('body', $data)) {
            throw new InvalidParameterException('No body is defined');
        }

        if (!array_key_exists('subject', $data)) {
            throw new InvalidParameterException('No subject is defined');
        }

        if (!is_numeric($data['user'])) {
            $data['user'] = $this->getIdFromIri($data['user']);
        }

        $recipent = $this->entityManager->find('AppBundle:User', $data['user']);

        /** @var \AppBundle\Entity\Message $message */
        $message = $this->composer
            ->newThread()
            ->setSender($this->getUser())
            ->setBody($data['body'])
            ->addRecipient($recipent)
            ->setSubject($data['subject'])
            ->getMessage();

        $this->sender->send($message);

        return $message->getThread();
    }

    /**
     * @param string $iri
     *
     * @return mixed
     */
    public function getIdFromIri(string $iri)
    {
        preg_match('/(\d+)/', $iri, $matches);

        return $matches[0];
    }
}