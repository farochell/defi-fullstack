<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Application\CalculateRoute;

use App\Shared\Domain\Bus\Command\CommandResponse;
use function Lambdish\Phunctional\map;

class StationsResponse implements CommandResponse {

    /**
     * @param array<int, array<string, mixed>> $stations
     */
    public function __construct(
        public array $stations
    ) {}

    /**
     * @param array<int, array<string, mixed>> $stations
     * @return StationsResponse
     */
    public static function fromEntities(array $stations): StationsResponse {
        return new self(
            map(
                static fn(
                    array $station
                ): StationResponse => StationResponse::fromEntity($station),
                $stations
            )
        );
    }
}
