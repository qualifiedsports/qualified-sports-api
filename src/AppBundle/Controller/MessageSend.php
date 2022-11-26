<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MediaObject;
use AppBundle\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidParameterException;

/**
 * Class MessageSend
 *
 * @package AppBundle\Controller
 */
class MessageSend extends Controller
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
     *     name="messages_send",
     *     path="/api/messages/send",
     *     defaults={
     *          "_api_resource_class"=Message::class,
     *          "_api_item_operation_name"="send",
     *          "_api_receive"=false
     *     }
     * )
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!array_key_exists('thread', $data)) {
            throw new InvalidParameterException('No thread is defined');
        }

        if (!array_key_exists('body', $data)) {
            throw new InvalidParameterException('No body is defined');
        }

        if (!is_numeric($data['thread'])) {
            $data['thread'] = $this->getIdFromIri($data['thread']);
        }

        $message = $this->composer->reply($this->provider->getThread($data['thread']))
            ->setSender($this->getUser())
            ->setBody($data['body'])
            ->getMessage();

        $this->sender->send($message);

        if (array_key_exists('attachment', $data) &&
            is_string($data['attachment']) &&
            ($attachmentId = $this->getAttachmentId($data)) &&
            ($attachment = $this->getDoctrine()->getRepository(MediaObject::class)->find($attachmentId))
        ) {
            /** @var $message Message */
            $message->setAttachment($attachment);
            $this->entityManager->flush();
        }

        return new JsonResponse(['data' => null], 201);
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

    private function getAttachmentId(array $data) {
        $re = '/\/(\d+)$/m';
        $str = $data['attachment'];

        preg_match($re, $str, $matches);

        if (count($matches) > 0) {
            return intval($matches[1]);
        }

        return null;
    }
}