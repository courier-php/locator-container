<?php
declare(strict_types = 1);

namespace Courier\Locators;

use Courier\Contracts\Locators\LocatorInterface;
use Courier\Contracts\Processors\HandlerInterface;
use Courier\Contracts\Processors\ListenerInterface;
use Courier\Exceptions\LocatorException;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;

/**
 * Fetch handler/listener instances from a PSR-11 compatible container.
 */
final class ContainerLocator implements LocatorInterface {
  private ContainerInterface $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function instanceFor(string $class): HandlerInterface|ListenerInterface {
    $implements = class_implements($class);
    if ($implements === false) {
      throw new InvalidArgumentException("Could not find a definition for class \"{$class}\"");
    }

    if (
      in_array(HandlerInterface::class, $implements, true) === false &&
      in_array(ListenerInterface::class, $implements, true) === false
    ) {
      throw new LocatorException("Class \"{$class}\" does not implement HandlerInterface nor ListenerInterface");
    }

    if ($this->container->has($class) === false) {
      throw new LocatorException("Could not find an instance for class \"{$class}\"");
    }

    return $this->container->get($class);
  }
}
