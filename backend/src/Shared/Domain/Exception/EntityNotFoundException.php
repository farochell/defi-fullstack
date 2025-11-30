<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use Throwable;

class EntityNotFoundException extends RepositoryException
{
    public static function withId(string $entityName, string $id): self
    {
        return new self(sprintf("Entity %s with ID %s not found", $entityName, $id));
    }

    public static function fromPrevious(string $entityName, Throwable $throwable): self
    {
        return new self(
            sprintf("Error while saving the entity %s: %s", $entityName, $throwable->getMessage()),
            0,
            $throwable
        );
    }
}
