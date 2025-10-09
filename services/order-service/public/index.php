<?php
// order-service/public/index.php - с отладкой
use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload.php';

// Отладка
error_log("=== SYMFONY KERNEL DEBUG ===");
error_log("Kernel class exists: " . (class_exists(Kernel::class) ? 'YES' : 'NO'));

if (class_exists(Kernel::class)) {
    $kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? false));
    error_log("Kernel created successfully");
    error_log("Kernel environment: " . $kernel->getEnvironment());

    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    error_log("Request path: " . $request->getPathInfo());

    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} else {
    error_log("Kernel class not found!");
    http_response_code(500);
    echo "Kernel not found";
}