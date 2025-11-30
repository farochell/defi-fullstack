<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Application\GetAnalyticDistance;

use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Bus\Query\QueryResponse;

class GetAnalyticDistanceQueryHandler implements QueryHandler {

    public function __invoke(GetAnalyticDistanceQuery $query): QueryResponse
    {
    }
}
