<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
use LogicException;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\reindex;

final class CallableFirstParameterExtractor
{
    /**
     * @param iterable<callable> $callables
     * @return array<string, array<callable>>
     */
    public static function forCallables(iterable $callables): array
    {
        return map(self::unflatten(), reindex(self::classExtractor(new self()), $callables));
    }

    /**
     * @param iterable<callable> $callables
     * @return DomainEventSubscriber
     */
    public static function forPipedCallables(iterable $callables): array
    {
        return reduce(self::pipedCallablesReducer(), $callables, []);
    }

    /**
     * @return callable(callable): ?string
     */
    private static function classExtractor(CallableFirstParameterExtractor $callableFirstParameterExtractor): callable
    {
        return static fn(callable $handler): ?string => $callableFirstParameterExtractor->extract($handler);
    }

    /**
     * @return callable(array<class-string<DomainEvent>, array<DomainEventSubscriber>>, DomainEventSubscriber): array<class-string<DomainEvent>, array<DomainEventSubscriber>>
     */
    private static function pipedCallablesReducer(): callable
    {
        return static function (array $subscribers, DomainEventSubscriber $domainEventSubscriber): array {
            $subscribedEvents = $domainEventSubscriber::subscribedTo();
            foreach ($subscribedEvents as $subscribedEvent) {
                /** @var class-string<DomainEvent> $subscribedEvent */
                $subscribers[$subscribedEvent][] = $domainEventSubscriber;
            }

            return $subscribers;
        };
    }

    /**
     * @return callable(mixed): array{mixed}
     */
    private static function unflatten(): callable
    {
        return static fn($value): array => [$value];
    }

    /**
     * @param callable $callable
     * @return string|null
     * @throws ReflectionException
     */
    public function extract(callable $callable): ?string
    {
        if ($callable instanceof \Closure) {
            // On crée un ReflectionFunction pour la closure
            $reflectionFunction = new \ReflectionFunction($callable);
            $params = $reflectionFunction->getParameters();
            if (count($params) === 1 && $params[0]->getType() instanceof \ReflectionNamedType) {
                return $params[0]->getType()->getName();
            }
            return null;
        }

        if (is_array($callable)) {
            $objectOrClass = $callable[0];
            $method = $callable[1];
            $reflectionMethod = new \ReflectionMethod($objectOrClass, $method);
            $params = $reflectionMethod->getParameters();
            if (count($params) === 1 && $params[0]->getType() instanceof \ReflectionNamedType) {
                return $params[0]->getType()->getName();
            }
            return null;
        }

        // Si c’est un objet invocable
        if (is_object($callable)) {
            $reflectionMethod = new \ReflectionMethod($callable, '__invoke');
            if ($this->hasOnlyOneParameter($reflectionMethod)) {
                return $this->firstParameterClassFrom($reflectionMethod);
            }
            return null;
        }

        throw new \LogicException('Unsupported callable type');
    }


    private function firstParameterClassFrom(ReflectionMethod $reflectionMethod): string
    {
        /** @var ?ReflectionNamedType $fistParameterType */
        $fistParameterType = $reflectionMethod->getParameters()[0]->getType();

        if (null === $fistParameterType) {
            throw new LogicException('Missing type hint for the first parameter of __invoke');
        }

        return $fistParameterType->getName();
    }

    private function hasOnlyOneParameter(ReflectionMethod $reflectionMethod): bool
    {
        return $reflectionMethod->getNumberOfParameters() === 1;
    }
}
