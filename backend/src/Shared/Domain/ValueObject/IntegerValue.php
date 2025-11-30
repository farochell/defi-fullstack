<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  automarkt
 */
declare(strict_types=1);


namespace App\Shared\Domain\ValueObject;

abstract class IntegerValue
{
    final private function __construct(protected int $value)
    {
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function isEqual(self $other): bool
    {
        return $this->value() === $other->value();
    }

    public function isLowerThan(self $other): bool
    {
        return $this->value() < $other->value();
    }

    public function isBiggerThan(self $other): bool
    {
        return $this->value() > $other->value();
    }
}