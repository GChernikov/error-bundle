<?php

declare(strict_types=1);

namespace GChernikov\ErrorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class ErrorExtension extends Extension
{
    /**
     * @inheritDoc
     * @codingStandardsIgnoreStart
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        /** @codingStandardsIgnoreEnd */
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
    }
}
