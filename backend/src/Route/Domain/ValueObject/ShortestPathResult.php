<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\ValueObject;

readonly class ShortestPathResult
{
    public function __construct(
        public Stations $stations,
        public float $distanceKm
    ) {
        if ($distanceKm < 0) {
            throw new \InvalidArgumentException("La distance ne peut pas être négative");
        }

        if ($this->stations->isEmpty()) {
            throw new \InvalidArgumentException("Le chemin ne peut pas être vide");
        }
    }
}
