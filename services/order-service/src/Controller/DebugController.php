<?php
// order-service/src/Controller/DebugController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DebugController
{
    public function index(): JsonResponse
    {
        $debugInfo = [
            'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'not set',
            'PATH_INFO' => $_SERVER['PATH_INFO'] ?? 'not set',
            'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? 'not set',
            'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'] ?? 'not set',
            'ORIGINAL_PATH' => $_SERVER['ORIGINAL_PATH'] ?? 'not set',
        ];

        return new JsonResponse($debugInfo);
    }
}