<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use OpenTok\OpenTok;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Chat;

class ChatGetToken extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OpenTok
     */
    private $openTok;

    /**
     * ChatGetToken constructor.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        OpenTok $openTok
    ) {
        $this->entityManager = $entityManager;
        $this->openTok = $openTok;
    }

    /**
     * @Route(
     *     name="chat_generate_token",
     *     path="/api/chat/token",
     *     methods={"POST"}
     * )
     *
     * @return Chat
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->find($data->user);

        if (!$user) {
            return new JsonResponse([], 400);
        }

        $me = $this->getUser();

        $chat = $this->getChat($me, $user);

        $token = $this->openTok->generateToken($chat->getSession());

        return JsonResponse::create([
            'session' => $chat->getSession(),
            'token' => $token
        ]);
    }

    private function getChat(User $firstUser, User $secondUser): Chat
    {
        try {
            $qb = $this->entityManager->createQueryBuilder();

            $qb->select('c')
                ->from(Chat::class, 'c')
                ->where(':user_one MEMBER OF c.users')
                ->andWhere(':user_two MEMBER OF c.users')
                ->setParameters([
                    'user_one' => $firstUser,
                    'user_two' => $secondUser
                ])
                ->setMaxResults(1);

            $chat = $qb->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {
            $session = $this->openTok->createSession();

            $chat = new Chat();
            $chat->setSession($session->getSessionId());
            $chat->addUser($firstUser);
            $chat->addUser($secondUser);

            $this->entityManager->persist($chat);
            $this->entityManager->flush();
        }

        return $chat;
    }
}