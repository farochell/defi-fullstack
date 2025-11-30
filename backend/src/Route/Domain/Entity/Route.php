<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\Entity;

use App\Route\Domain\ValueObject\Stations;
use App\Route\Domain\ValueObject\RouteId;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Route extends AggregateRoot
{
    public function __construct(
        public readonly RouteId $id,
        public readonly Station $fromStation,
        public readonly Station $toStation,
        public readonly string $analyticCode,
        public readonly float $distanceKm,
        public readonly Stations $path,
        public readonly \DateTimeImmutable $createdAt
    ) {}

    public static function create(
        Station $fromStation,
        Station $toStation,
        string $analyticCode,
        float $distanceKm,
        Stations $path,
    ): Route {
        return new self(
            RouteId::random(),
            $fromStation,
            $toStation,
            $analyticCode,
            $distanceKm,
            $path,
            new \DateTimeImmutable()
        );
    }
}
