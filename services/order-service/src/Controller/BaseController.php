<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class BaseController
{
    use ContainerAwareTrait;

    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }
}
