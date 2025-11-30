<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Infrastructure\Repository;

use App\Route\Domain\Entity\Station;
use App\Route\Domain\Repository\StationRepositoryInterface;
use App\Route\Domain\ValueObject\StationId;
use App\Route\Domain\ValueObject\Stations;

class JsonStationRepository implements StationRepositoryInterface
{
    private Stations $stations;

    public function __construct(string $stationsFile)
    {
        $data = json_decode((string) file_get_contents($stationsFile), true);
        $stations = [];
        foreach ($data as $item) {
            $station = new Station(
                id: StationId::fromInt($item['id']),
                shortName: $item['shortName'],
                longName: $item['longName']
            );

            $stations[] = $station;
        }
        $this->stations = new Stations($stations);
    }

    public function findByShortName(string $shortName): ?Station
    {
        return $this->stations->findByShortName($shortName);
    }
}
