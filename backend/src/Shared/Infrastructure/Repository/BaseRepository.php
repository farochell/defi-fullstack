<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Shared\Infrastructure\Repository;

use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Exception\RepositoryException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @template T of object
 * @extends ServiceEntityRepository<T>
 */
abstract class BaseRepository  extends ServiceEntityRepository
{
    protected string $entityClass;

    public function __construct(
        ManagerRegistry $managerRegistry,
        string $entityClass,
        protected string $entityName,
        protected LoggerInterface $logger
    ) {
        parent::__construct($managerRegistry, $entityClass);
        $this->entityClass = $entityClass;
    }

    protected function logAndThrowNotFoundException(string $id): void
    {
        $entityNotFoundException = EntityNotFoundException::withId($this->entityName, $id);
        $this->logger->error($entityNotFoundException->getMessage(), [
            'exception' => $entityNotFoundException,
            'entity' => $this->entityName,
            'id' => $id
        ]);
    }

    /**
     * @param RepositoryException $repositoryException
     * @param object $entity
     * @param array<string, mixed> $context Contexte additionnel pour le log
     *
     * @return void
     *
     * @throws RepositoryException
     */
    protected function logAndThrowException(
        RepositoryException $repositoryException,
        object $entity,
        array $context = []
    ): void  {
        $this->logger->error($repositoryException->getMessage(), array_merge([
            'exception' => $repositoryException,
            'entity' => $this->entityName,
            'entity_id' => method_exists($entity, 'id')
                ? $entity->id()->value()
                : 'unknown'
        ], $context));

        throw $repositoryException;
    }

    /**
     * @param object $entity
     * @return string[]
     */
    protected function serializeEntity(object $entity): array
    {
        return [
            'id' => method_exists($entity, 'id') ? $entity->id()->value() : 'unknown'
        ];
    }
}
