<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  automarkt
 */
declare(strict_types=1);


namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

class Uuid extends StringValueObject
{
    public function __construct(protected string $value)
    {
        $this->ensureIsValidUuid($value);
        parent::__construct($value);
    }

    public static function random(): static
    {
        return new static(SymfonyUuid::v4()->toRfc4122());
    }

    public function equals(Uuid $uuid): bool
    {
        return $this->value() === $uuid->value();
    }

    private function ensureIsValidUuid(string $value): void
    {
        if (! SymfonyUuid::isValid($value)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $value));
        }
    }
}