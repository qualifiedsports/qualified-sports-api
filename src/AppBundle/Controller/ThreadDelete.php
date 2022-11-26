<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Thread;
use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\Deleter\DeleterInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ThreadDelete
 *
 * @package AppBundle\Controller
 */
class ThreadDelete
{
    /**
     * @var \FOS\MessageBundle\Provider\ProviderInterface
     */
    private $provider;
    /**
     * @var \FOS\MessageBundle\Deleter\DeleterInterface
     */
    private $deleter;
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * ThreadDelete constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface          $entityManager
     * @param \FOS\MessageBundle\Provider\ProviderInterface $provider
     * @param \FOS\MessageBundle\Deleter\DeleterInterface   $deleter
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ProviderInterface $provider,
        DeleterInterface $deleter
    )
    {
        $this->provider = $provider;
        $this->deleter = $deleter;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(
     *     name="threads_delete",
     *     path="/api/threads/{id}",
     *     methods={"DELETE"},
     *     defaults={"_api_resource_class"=Thread::class, "_api_collection_operation_name"="delete",
     *     "_api_receive"=false}
     * )
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $thread = $this->provider->getThread($request->get('id'));

        $this->deleter->markAsDeleted($thread);

        $this->entityManager->flush();

        return new JsonResponse(['data' => null], 201);
    }
}