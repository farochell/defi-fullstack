<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\Service;

use App\Route\Domain\Entity\Station;
use App\Route\Domain\ValueObject\ShortestPathResult;

interface ShortestPathFinderInterface
{
    public function findShortestPath(
        RailNetworkInterface $network,
        Station $from,
        Station $to,
    ): ShortestPathResult;
}
