<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Application\GetAnalyticDistances;

use App\Shared\Domain\Bus\Query\Query;

class GetAnalyticDistancesQuery implements Query
{
    public function __construct(
        public readonly ?string $from = null,
        public readonly ?string $to = null,
        public readonly ?string $groupBy = null,
    ) {}
}
