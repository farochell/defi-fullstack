<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\Entity;

use App\Route\Domain\ValueObject\StationId;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Station extends AggregateRoot
{
    public function __construct(
        public readonly StationId $id,
        public readonly string $shortName,
        public readonly string $longName,
    ) {}

    public static function create(
        int $id,
        string $shortName,
        string $longName
    ): Station {
        return new self(
            StationId::fromInt($id),
            $shortName,
            $longName,
        );
    }
}
