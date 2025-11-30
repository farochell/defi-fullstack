<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Application\CalculateRoute;

use App\Route\Domain\Entity\Station;
use App\Route\Domain\ValueObject\Stations;
use function Lambdish\Phunctional\map;

class StationsFormatter {
    /**
     * @param Stations $stations
     * @return array<array<string, mixed>>
     */
    public static function format(Stations $stations): array {
        return map(
            static fn(Station $station) => [
                'id' => $station->id,
                'shortName' => $station->shortName,
                'longName' => $station->longName
            ],
            $stations->getIterator()->getArrayCopy()
        );
    }
}
