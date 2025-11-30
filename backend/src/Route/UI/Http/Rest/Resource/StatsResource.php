<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use App\Route\UI\Http\Rest\Controller\GetAnalyticDistancesController;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/stats/distances',
            inputFormats: ['json' => ['application/json']],
            status: 200,
            controller: GetAnalyticDistancesController::class,
           openapi: new Operation(
               operationId: 'getAnalyticDistances',
               tags: ['Stats'],
                summary: 'BONUS : Distances agrégées par code analytique',
                description: 'Retourne la somme des distances parcourues par code analytique sur une période donnée.
                Si aucune période n\'est fournie, utilise la période complète disponible',
                parameters: [
                    new Parameter(
                        name: 'from',
                        in: 'query',
                        description: 'Date de début (inclus)',
                        required: false,
                        schema: ['type' => 'string']
                    ),
                    new Parameter(
                        name: 'to',
                        in: 'query',
                        description: 'Date de fin (inclus)',
                        required: false,
                        schema: ['type' => 'string']
                    ),
                    new Parameter(
                        name: 'groupBy',
                        in: 'query',
                        description: 'Optionnel, groupement additionnel',
                        required: false,
                        schema: ['type' => 'enum', 'enum' => ['day', 'month', 'year', 'none']]
                    )
                ]
            )
        )
    ]
)]
class StatsResource {}
