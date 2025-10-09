<?php
namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $container): void
    {
        $container->loadFromExtension("framework", [
            "secret" => "test",
            "router" => ["resource" => "kernel::loadRoutes"],
        ]);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import("../config/routes.yaml");
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }
}
