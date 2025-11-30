<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Route\Application\GetAnalyticDistances\GetAnalyticDistancesQuery;
use App\Route\UI\Http\Rest\Formatter\ErrorFormatterTrait;
use App\Route\UI\Http\Rest\Input\GetAnalyticInput;
use App\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

#[AsController]
class GetAnalyticDistancesController{
    use ErrorFormatterTrait;

    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function __invoke(
#[MapQueryString] ?GetAnalyticInput $analyticInput = null
    ) : JsonResponse {
        try {

            //$this->validator->validate($analyticInput);
            $response = $this->queryBus->ask(
                new GetAnalyticDistancesQuery(
                    $analyticInput?->from,
                    $analyticInput?->to,
                    $analyticInput?->groupBy
                )
            );
            return new JsonResponse($response);
        } catch (\Throwable $e) {
            $errorResponse = $this->formatError($e);

            // Si formatError retourne null, créer une réponse par défaut
            if ($errorResponse === null) {
                return new JsonResponse(
                    ['error' => $e->getMessage()],
                    500
                );
            }

            return $errorResponse;
        }
    }
}
