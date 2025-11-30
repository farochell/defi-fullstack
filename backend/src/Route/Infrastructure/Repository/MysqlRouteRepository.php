<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Infrastructure\Repository;

use App\Route\Domain\Entity\Route;
use App\Route\Domain\Entity\Station;
use App\Route\Domain\Repository\RouteRepositoryInterface;
use App\Route\Infrastructure\Doctrine\Mapping\DoctrineRoute;
use App\Shared\Domain\Exception\EntityPersistenceException;
use App\Shared\Infrastructure\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use function Lambdish\Phunctional\map;

class MysqlRouteRepository extends BaseRepository implements RouteRepositoryInterface {
    public function __construct(
        ManagerRegistry $managerRegistry,
        LoggerInterface $logger,
    ) {
        parent::__construct(
            $managerRegistry,
            DoctrineRoute::class,
            'Route',
            $logger
        );
    }

    public function save(Route $route): void
    {
        try {
            $entity = $this->toDoctrineEntity($route);
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        } catch (\Throwable $e) {
            $exception = EntityPersistenceException::fromPrevious($this->entityName, $e);
            $this->logAndThrowException($exception, $route, [
                'data' => $this->serializeEntity($route)
            ]);
        }
    }

    private function toDoctrineEntity(Route $route): DoctrineRoute {
        $paths = map(
            static fn(Station $station) => [
                'id' => $station->id,
                'shortName' => $station->shortName,
                'longName' => $station->longName
            ],
            $route->path->getIterator()->getArrayCopy()
        );

        $doctrineRoute = new DoctrineRoute();
        $doctrineRoute->id = $route->id;
        $doctrineRoute->fromStation = $route->fromStation->shortName;
        $doctrineRoute->toStation = $route->toStation->shortName;
        $doctrineRoute->distanceKm = $route->distanceKm;
        $doctrineRoute->analyticCode = $route->analyticCode;
        $doctrineRoute->path = $paths;
        $doctrineRoute->createdAt = $route->createdAt;

        return $doctrineRoute;
    }
}
