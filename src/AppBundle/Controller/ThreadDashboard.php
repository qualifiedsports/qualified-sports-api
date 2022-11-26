<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Thread;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageSend
 *
 * @package AppBundle\Controller
 */
class ThreadDashboard extends Controller
{
    /**
     * @var \FOS\MessageBundle\Security\ParticipantProviderInterface
     */
    private $participantProvider;

    /**
     * @var \FOS\MessageBundle\ModelManager\ThreadManagerInterface
     */
    private $threadManager;

    /**
     * MessageSend constructor.
     *
     * @param \FOS\MessageBundle\Security\ParticipantProviderInterface $participantProvider
     * @param \FOS\MessageBundle\ModelManager\ThreadManagerInterface $threadManager
     */
    public function __construct(
        ParticipantProviderInterface $participantProvider,
        ThreadManagerInterface $threadManager
    ) {
        $this->participantProvider = $participantProvider;
        $this->threadManager = $threadManager;
    }

    /**
     * @Route(
     *     name="threads_my_dashboard",
     *     path="/api/threads/my/dashboard",
     *     methods={"GET"},
     *     defaults={"_api_resource_class"=Thread::class, "_api_collection_operation_name"="my_dashboard", "_api_receive"=false}
     * )
     * @return \FOS\MessageBundle\Model\ThreadInterface[]
     */
    public function __invoke()
    {
        $new = [];

        foreach ($this->getInboxThreads(1, 3) as $thread) {
            if (!$thread->isReadByParticipant($this->getUser())) {
                array_push($new, $thread);
            }
        }

        return $new;
    }

    private function getInboxThreads($page, $perPage)
    {
        $participant = $this->participantProvider->getAuthenticatedParticipant();

        return $this->threadManager
            ->getParticipantInboxThreadsQueryBuilder($participant)
            ->setFirstResult($perPage * ($page-1))
            ->setMaxResults($perPage)
            ->getQuery()
            ->execute();
    }
}