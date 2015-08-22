<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Hangman\InMemoryWordRepository;
use AppBundle\Entity\Game;
use AppBundle\Entity\Word;

final class ApiController extends Controller
{
    /**
     * @Route("/game", name="game")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function gameAction()
    {
        /** @var InMemoryWordRepository $wordRepository */
        $wordRepository = $this->get('word_repository');
        $word = $wordRepository->pickARandomWord();
        $wordEntity = Word::fromString($word->value());

        /** @var Game $game */
        $game = Game::withWord($wordEntity);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }

        $nextUrl = $this->generateUrl(
            'guess',
            ['id' => $game->getId()->toString()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse(
            [
                'data' => [
                    'id'    => $game->getId()->toString(),
                    'links' => [
                        'self' => $nextUrl
                    ]
                ]
            ],
            201
        );
    }

    /**
     * @Route("/game/{id}", name="guess")
     * @Method("PUT")
     * @ParamConverter("game", class="AppBundle:Game")
     *
     * @param Request $request
     * @param Game    $game
     *
     * @return JsonResponse
     */
    public function guessAction(Request $request, Game $game)
    {
        try {
            $game->guess($request->get('letter'));

            $em = $this->getDoctrine()->getManager();
            $em->flush();
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }

        return new JsonResponse(
            [
                'data' => [
                    'word'       => $game->obfuscatedWord(),
                    'tries_left' => $game->guessesRemaining(),
                    'status'     => $game->status()
                ]
            ]
        );
    }

    /**
     * @param \Exception $e
     *
     * @return JsonResponse
     */
    private function errorResponse(\Exception $e)
    {
        return new JsonResponse(
            [
                'error' => [
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage()
                ]
            ],
            500
        );
    }
}
