<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Route\UI\Http\Rest\Controller\CalculerRouteController;
use App\Route\UI\Http\Rest\Input\RouteInput;
use ArrayObject;
use ApiPlatform\OpenApi\Model\Response;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/routes',
            inputFormats: ['json' => ['application/json']],
            status: 201,
            controller: CalculerRouteController::class,
            openapi: new Operation(
                operationId: 'calculerRoute',
                tags: ['Routing'],
                responses: [
                    '201' => new Response(
                        description: 'Trajet calculé',
                        content: new ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'id' => ['type' => 'string'],
                                        'fromStationId' => ['type' => 'string'],
                                        'toStationId' => ['type' => 'string'],
                                        'analyticCode' => ['type' => 'string'],
                                        'distanceKm' => ['type' => 'number', 'format' => 'float'],
                                        'path' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'object',
                                                'properties' => [
                                                    'id' => ['type' => 'string'],
                                                    'name' => ['type' => 'string'],
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ])
                    ),
                    '422' => new Response(
                        description: 'Données non valides (ex. station inconnue, réseau non connexe)',
                        content: new ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'code' => ['type' => 'string'],
                                        'message' => ['type' => 'string'],
                                        'details' => ['type' => 'array', 'items' => ['type' => 'string']],
                                    ]
                                ]
                            ]
                        ])
                    ),
                    '400' => new Response(
                        description: 'Requête invalide',
                        content: new ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'code' => ['type' => 'string'],
                                        'message' => ['type' => 'string'],
                                        'details' => ['type' => 'array', 'items' => ['type' => 'string']],
                                    ]
                                ]
                            ]
                        ])
                    ),
                ],
                summary: 'Calculer un trajet A → B',
                description: 'Calculer un trajet A → B',
                requestBody: new RequestBody(
                    content: new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['fromStationId', 'toStationId', 'analyticCode'],
                                'properties' => [
                                    'fromStationId' => [
                                        'type' => 'string',
                                        'description' => 'ID de la station de départ'
                                    ],
                                    'toStationId' => [
                                        'type' => 'string',
                                        'description' => 'ID de la station d\'arrivée'
                                    ],
                                    'analyticCode' => [
                                        'type' => 'string',
                                        'description' => 'Code analytique'
                                    ],
                                ]
                            ],
                            'example' => [
                                'fromStationId' => 'MX',
                                'toStationId' => 'ZW',
                                'analyticCode' => 'ANA-123',
                            ]
                        ]
                    ]),
                ),
            ),
            input: RouteInput::class,
            messenger: false,
            read: false,
            write: false,
        )
    ]
)]
class RouteResource {}
