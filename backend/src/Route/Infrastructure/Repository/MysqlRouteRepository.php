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
use App\Route\Domain\ValueObject\GroupBy;
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

    public function getAnalyticDistances(
        ?\DateTimeImmutable $from,
        ?\DateTimeImmutable $to,
        GroupBy $groupBy
    ): array {
        $conn = $this->getEntityManager()->getConnection();

        // Si from ou to manquent, récupérer la période complète disponible en base
        if (null === $from || null === $to) {
            $minMaxSql = 'SELECT MIN(created_at) AS min_dt, MAX(created_at) AS max_dt FROM routes';
            $minMax = $conn->fetchAssociative($minMaxSql);

            if (!$minMax || $minMax['min_dt'] === null || $minMax['max_dt'] === null) {
                return []; // Pas de données
            }

            $from = $from ?? new \DateTimeImmutable($minMax['min_dt']);
            $to = $to ?? new \DateTimeImmutable($minMax['max_dt']);
        }

        // Normaliser les bornes (inclusives)
        $fromStr = $from->format('Y-m-d 00:00:00');
        $toStr = $to->format('Y-m-d 23:59:59');

        // Déterminer l'expression de groupement
        $groupExpr = match ($groupBy) {
            GroupBy::DAY => "DATE_FORMAT(created_at, '%Y-%m-%d')",
            GroupBy::MONTH => "DATE_FORMAT(created_at, '%Y-%m')",
            GroupBy::YEAR => "DATE_FORMAT(created_at, '%Y')",
            GroupBy::NONE => null,
        };

        // Construire la requête SQL
        $selectFields = [
            'analytic_code AS analyticCode',
            'SUM(distance_km) AS totalDistanceKm',
            'DATE(MIN(created_at)) AS periodStart',
            'DATE(MAX(created_at)) AS periodEnd'
        ];

        $groupByFields = ['analytic_code'];
        $orderByFields = ['analytic_code'];

        if ($groupExpr !== null) {
            array_splice($selectFields, 1, 0, ["{$groupExpr} AS `group`"]);
            $groupByFields[] = '`group`';
            $orderByFields[] = '`group`';
        }

        $sql = sprintf(
            'SELECT %s FROM routes WHERE created_at BETWEEN :from AND :to GROUP BY %s ORDER BY %s',
            implode(', ', $selectFields),
            implode(', ', $groupByFields),
            implode(', ', $orderByFields)
        );

        $rows = $conn->fetchAllAssociative($sql, ['from' => $fromStr, 'to' => $toStr]);

        // Mapper les résultats
        return array_map(static function (array $r) use ($groupExpr): array {
            return [
                'analyticCode' => (string) $r['analyticCode'],
                'totalDistanceKm' => (float) $r['totalDistanceKm'],
                'periodStart' => (string) $r['periodStart'],
                'periodEnd' => (string) $r['periodEnd'],
                'group' => $groupExpr !== null ? (string) $r['group'] : null,
            ];
        }, $rows);
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
