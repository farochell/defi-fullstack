<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Application\CalculateRoute;

use App\Shared\Domain\Bus\Command\CommandResponse;

class StationResponse implements CommandResponse
{
    public function __construct(
        public string $id,
        public string $shortName,
        public string $longName
    ) {}

    /**
     * @param array<string, mixed> $station
     * @return self
     */
    public static function fromEntity(array $station): self {
        return new self(
            (string) $station['id']->value(),
            $station['shortName'],
            $station['longName']
        );
    }
}
