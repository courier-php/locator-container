<?php
declare(strict_types = 1);

namespace Courier\Locator;

use Courier\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;

final class ContainerLocator implements LocatorInterface {
  private ContainerInterface $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function getInstanceFor(string $class): ?ProcessorInterface {
    if ($this->container->has($class)) {
      return $this->container->get($class);
    }

    return null;
  }
}
