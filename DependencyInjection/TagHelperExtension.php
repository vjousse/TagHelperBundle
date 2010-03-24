<?php

namespace Bundle\TagHelperBundle\DependencyInjection;

use Symfony\Components\DependencyInjection\Loader\LoaderExtension;
use Symfony\Components\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Components\DependencyInjection\BuilderConfiguration;

/**
 * TagHelperExtension manages the template helper.
 */
class TagHelperExtension extends LoaderExtension
{
  
  public function tagLoad($config)
  {
    $configuration = new BuilderConfiguration();

    $loader = new XmlFileLoader(__DIR__.'/../Resources/config');
    $configuration->merge($loader->load('tag.xml'));

    return $configuration;
  }

  /**
   * Returns the base path for the XSD files.
   *
   * @return string The XSD base path
   */
  public function getXsdValidationBasePath()
  {
    return null;
  }

  public function getNamespace()
  {
    return 'http://www.symfony-project.org/schema/dic/symfony';
  }

  public function getAlias()
  {
    return 'helper';
  }
}
