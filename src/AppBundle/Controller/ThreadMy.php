<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Thread;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageSend
 *
 * @package AppBundle\Controller
 */
class ThreadMy extends Controller
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
     *     name="threads_my",
     *     path="/api/threads/my",
     *     methods={"GET"},
     *     defaults={"_api_resource_class"=Thread::class, "_api_collection_operation_name"="my", "_api_receive"=false}
     * )
     *
     * @return \FOS\MessageBundle\Model\ThreadInterface[]
     */
    public function __invoke(Request $request)
    {
        // ?order%5Bid%5D=ASC&_page=1&_itemsPerPage=1000
        $query = $request->query->all();
        $page = $query['_page'];
        $perPage = $query['_itemsPerPage'];
        $type = $query['type'];

        $threads = 'inbox' === $type ? $this->getInboxThreads($page, $perPage) : $this->getSentThreads($page, $perPage);
        $count = 'inbox' === $type ? $this->getInboxThreadsCount() : $this->getSentThreadsCount();

        array_walk($threads, function (Thread $thread) {
            $thread->isReadByParticipant($this->getUser());
        });
        usort($threads, function (Thread $a, Thread $b) { return $a->getHasBeenRead() > $b->getHasBeenRead(); });

        header('Content-Range: ' . $count);

        return $threads;
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

    private function getInboxThreadsCount()
    {
        $participant = $this->participantProvider->getAuthenticatedParticipant();

        return $this->threadManager
            ->getParticipantInboxThreadsQueryBuilder($participant)
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function getSentThreads($page, $perPage)
    {
        $participant = $this->participantProvider->getAuthenticatedParticipant();

        return $this->threadManager
            ->getParticipantSentThreadsQueryBuilder($participant)
            ->setFirstResult($perPage * ($page-1))
            ->setMaxResults($perPage)
            ->getQuery()
            ->execute();
    }

    private function getSentThreadsCount()
    {
        $participant = $this->participantProvider->getAuthenticatedParticipant();

        return $this->threadManager
            ->getParticipantSentThreadsQueryBuilder($participant)
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}