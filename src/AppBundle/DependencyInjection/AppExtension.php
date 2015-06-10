<?php

/**
 * Description of AppExtension
 *
 * @author Jonathan Claros <jclaros at lysoftbo.com>
 */
namespace AppBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/*
 * With this we force the framework to load services.yml file
 */
class AppExtension extends Extension {
  
  public function load(array $config, ContainerBuilder $container) {
    $loader = new YamlFileLoader(
        $container,
        new FileLocator(__DIR__.'/../Resources/config')
    );
    $loader->load('services.yml');
  }
}
