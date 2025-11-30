<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  automarkt
 */
declare(strict_types=1);


namespace App\Shared\Domain\Exception;

use Throwable;

class EntityPersistenceException extends RepositoryException
{
    public static function fromPrevious(string $entityName, Throwable $throwable): self
    {
        return new self(
            sprintf("Error while saving the entity %s: %s", $entityName, $throwable->getMessage()),
            0,
            $throwable
        );
    }
}
