<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

final class ExceptionController extends Controller
{
    /**
     * @param FlattenException     $exception
     * @param DebugLoggerInterface $logger
     *
     * @return JsonResponse
     */
    public function showExceptionAction(FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        return new JsonResponse(
            [
                'error' => [
                    'code'    => $exception->getCode(),
                    'message' => $exception->getMessage()
                ]
            ],
            $exception->getStatusCode()
        );
    }
}
