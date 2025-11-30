<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Controller;

use App\Route\Application\CalculateRoute\CalculateRouteCommand;
use App\Route\UI\Http\Rest\Formatter\ErrorFormatterTrait;
use App\Route\UI\Http\Rest\Input\RouteInput;
use App\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use ApiPlatform\Validator\ValidatorInterface;

#[AsController]
class CalculerRouteController extends  AbstractController{
   use ErrorFormatterTrait;

    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ValidatorInterface $validator
    ) {}

    public function __invoke(
        RouteInput $input
    ): JsonResponse {
        try {
            $this->validator->validate($input);
            $result = $this->commandBus->dispatch(
                new CalculateRouteCommand(
                    $input->fromStationId,
                    $input->toStationId,
                    $input->analyticCode
                )
            );
            return JsonResponse::fromJsonString((string) json_encode($result), 200);
        } catch (\Throwable $e) {
            return $this->formatError($e);
        }
    }
}
