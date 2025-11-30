<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use App\Route\UI\Http\Rest\Controller\GetAnalyticDistancesController;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/stats/distances',
            status: 200,
            controller: GetAnalyticDistancesController::class,
            paginationEnabled: false,
            output: false,
            read: false,
            name: 'get_stats_distances'  // Important !
        )

    ]
)]
class StatsResource {}
