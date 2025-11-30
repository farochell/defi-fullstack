<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\ValueObject;

use App\Route\Domain\Entity\Station;

class DistanceLink
{
    public function __construct(
        public Station $from,
        public Station $to,
        public float $distanceKm
    ) {
        if ($distanceKm < 0) {
            throw new \InvalidArgumentException("Distance ne peut pas être négative");
        }
    }
}
