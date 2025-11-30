<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Formatter;

use ApiPlatform\Validator\Exception\ValidationException;
use App\Shared\Domain\Exception\ErrorCode;
use Symfony\Component\Validator\ConstraintViolationInterface;

class SymfonyValidationExceptionFormatter
{
    public static function format(ValidationException $exception): array
    {
        $details = [];

        foreach ($exception->getConstraintViolationList() as $violation) {
            /** @var ConstraintViolationInterface $violation */
            $propertyPath = $violation->getPropertyPath();
            $message = $violation->getMessage();

            $details[] = $propertyPath
                ? "{$propertyPath}: {$message}"
                : $message;
        }

        return [
            'code' => ErrorCode::MISSING_PARAMETERS,
            'message' => 'Les données suivantes sont manquantes:',
            'details' => $details,
        ];
    }
}
