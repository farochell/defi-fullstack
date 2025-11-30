<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Application\CalculateRoute;

use App\Route\Domain\Entity\Route;
use App\Route\Domain\Entity\Station;
use App\Shared\Domain\Bus\Command\CommandResponse;

class RouteResponse implements CommandResponse
{
    /**
     * @param string $id
     * @param string $fromStationId
     * @param string $toStationId
     * @param string $analyticCode
     * @param float $distanceKm
     * @param array<Station> $path
     * @param string $createdAt
     */
    public function __construct(
        public string $id,
        public string $fromStationId,
        public string $toStationId,
        public string $analyticCode,
        public float $distanceKm,
        public array $path,
        public string $createdAt
    ){}

    public static function fromEntity(Route $route): self
    {
        return new self(
            id: $route->id->value(),
            fromStationId: (string) $route->fromStation->id->value(),
            toStationId:  (string) $route->toStation->id->value(),
            analyticCode: $route->analyticCode,
            distanceKm: $route->distanceKm,
            path: (array)StationsResponse::fromEntities(StationsFormatter::format($route->path)),
            createdAt: $route->createdAt->format('Y-m-d H:i:s')
        );
    }
}
