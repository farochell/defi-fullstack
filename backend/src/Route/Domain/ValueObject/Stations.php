<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\ValueObject;

use App\Route\Domain\Entity\Station;
use App\Shared\Domain\Collection;
use function Lambdish\Phunctional\reindex;

/**
 * @extends Collection<Station>
 */
class Stations extends Collection {
    private ?array $indexedStationByIds = null;
    private ?array $indexedStationByShortname = null;

    protected function type(): string {
        return Station::class;
    }

    public function indexedStationByIds(): array {
        if ($this->indexedStationByIds === null) {
            $this->indexedStationByIds = reindex(
                static fn (Station $station) => $station->id->value(),
                $this->items()
            );
        }
        return $this->indexedStationByIds;
    }

    public function indexedStationByShortname(): array {
        if ($this->indexedStationByShortname === null) {
            $this->indexedStationByShortname = reindex(
                static fn (Station $station) => $station->shortName,
                $this->items()
            );
        }
        return $this->indexedStationByShortname;
    }

    public function findById(StationId $id): ?Station {
        $values = $this->indexedStationByIds();
        return $values[$id->value()] ?? null;
    }

    public function findByShortName(string $shortName): ?Station {
        $values = $this->indexedStationByShortname();
        return $values[$shortName] ?? null;
    }

    public function searchByLongName(string $query): ?Stations
    {
        $query = mb_strtolower($query); // pour insensible à la casse
        $results =  array_filter($this->items(), function(Station $station) use ($query) {
            return mb_stripos($station->longName, $query) !== false;
        });

        return new Stations($results);
    }
}
