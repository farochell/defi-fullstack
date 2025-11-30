<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Shared\Domain;

final class Assert {

    /**
     * @param string $class
     * @param array<mixed> $items
     * @return bool
     */
  public static function arrayOf(string $class, array $items): bool
  {
    foreach ($items as $item) {
      self::instanceOf($class, $item);
    }
    return true;
  }

  public static function instanceOf(string $class, object $item): void
  {
    if (!($item instanceof $class)) {
      throw new \InvalidArgumentException(sprintf('Expected instance of %s, got %s', $class, get_class($item)));
    }
  }
}
