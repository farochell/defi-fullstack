<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\Repository;

use App\Route\Domain\Entity\Station;

interface StationRepositoryInterface
{
    public function findByShortName(string $shortName): ?Station;
}
