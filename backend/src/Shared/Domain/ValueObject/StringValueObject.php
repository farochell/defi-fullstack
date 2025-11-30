<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);


namespace App\Shared\Domain\ValueObject;

use Stringable;

abstract class StringValueObject implements  StringValueObjectInterface, Stringable
{
    public function __construct(protected  string $value)
    {
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public function toString(): string
    {
        return $this->value;
    }
}
