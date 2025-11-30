<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Formatter;

use ApiPlatform\Validator\Exception\ValidationException;
use App\Route\Domain\Exception\StationNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

trait ErrorFormatterTrait {
    public function formatError(Throwable $error): ?JsonResponse
    {
        if ($error instanceof StationNotFoundException) {
            return new JsonResponse([
                'code'    => $error->getErrorCode(),
                'message' => $error->getMessage(),
                'details' => $error->getDetails(),
            ], 422);
        }

        if ($error instanceof ValidationException) {
            return new JsonResponse(SymfonyValidationExceptionFormatter::format($error), 400);
        }
        return null;
    }
}
