<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $services->defaults()
        ->autowire(true)
        ->autoconfigure()
        ->load('GChernikov\\ErrorBundle\\', '../../*')
        ->exclude('../../{DependencyInjection,Entity,Resources,Tests,Kernel.php}');
};
