<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Thread;
use FOS\MessageBundle\Provider\ProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageSend
 *
 * @package AppBundle\Controller
 */
class ThreadMySingle extends Controller
{
    /**
     * @var \FOS\MessageBundle\Provider\ProviderInterface
     */
    private $provider;

    /**
     * MessageSend constructor.
     *
     * @param \FOS\MessageBundle\Provider\ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @Route(
     *     name="threads_my_single",
     *     path="/api/threads/my/{id}",
     *     methods={"GET"},
     *     defaults={"_api_resource_class"=Thread::class, "_api_item_operation_name"="my", "_api_receive"=false}
     * )
     */
    public function __invoke(Request $request)
    {
        return $this->provider->getThread($request->get('id'));
    }
}