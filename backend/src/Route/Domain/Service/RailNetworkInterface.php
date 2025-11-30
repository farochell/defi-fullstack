<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\Service;

use App\Route\Domain\Entity\Station;
use App\Route\Domain\ValueObject\DistanceLinks;

interface RailNetworkInterface {
    public function getOutgoingLinks(Station $station): DistanceLinks;

    /**
     * Récupère une station par son shortName
     */
    public function getStationByShortName(string $shortName): ?Station;
}
