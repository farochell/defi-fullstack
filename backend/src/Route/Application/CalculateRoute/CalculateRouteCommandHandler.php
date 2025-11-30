<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Application\CalculateRoute;

use App\Route\Domain\Entity\Route;
use App\Route\Domain\Exception\StationNotFoundException;
use App\Route\Domain\Repository\RouteRepositoryInterface;
use App\Route\Domain\Repository\StationRepositoryInterface;
use App\Route\Domain\Service\RailNetworkInterface;
use App\Route\Domain\Service\ShortestPathFinderInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;

class CalculateRouteCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly StationRepositoryInterface $stationRepo,
        private readonly RailNetworkInterface $network,
        private readonly ShortestPathFinderInterface $shortestPathFinder,
        private readonly RouteRepositoryInterface $routeRepo
    ) {}

    public function __invoke(
        CalculateRouteCommand $command
    ): RouteResponse {
        $from = $this->stationRepo->findByShortName($command->fromStationId);
        $to = $this->stationRepo->findByShortName($command->toStationId);
        if (!$from) {
            throw new StationNotFoundException($command->fromStationId);
        }
        if (!$to) {
            throw new StationNotFoundException($command->toStationId);
        }
        $pathResult = $this->shortestPathFinder->findShortestPath(
            network: $this->network,
            from: $from,
            to: $to
        );
        $route = Route::create(
            fromStation: $from,
            toStation: $to,
            analyticCode: $command->analyticCode,
            distanceKm: $pathResult->distanceKm,
            path: $pathResult->stations
        );
        $this->routeRepo->save($route);
        return RouteResponse::fromEntity($route);
    }
}
