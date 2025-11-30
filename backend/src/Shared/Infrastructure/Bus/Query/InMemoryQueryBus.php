<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\QueryNotRegisteredError;
use App\Shared\Domain\Bus\Query\QueryResponse;
use App\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use App\Shared\Infrastructure\Bus\Middleware\LoggerMiddleware;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;

readonly class InMemoryQueryBus implements QueryBus
{
    private MessageBus $messageBus;

    /** @param iterable<callable> $queryHandlers */
    public function __construct(iterable $queryHandlers, LoggerInterface $logger)
    {
        $this->messageBus = new MessageBus(
            [
                new HandleMessageMiddleware(
                    new HandlersLocator(CallableFirstParameterExtractor::forCallables($queryHandlers))
                ),
                new LoggerMiddleware($logger),
            ]
        );
    }

    public function ask(Query $query): ?QueryResponse {
        try {
            /** @var HandledStamp $stamp */
            $stamp = $this->messageBus->dispatch($query)->last(HandledStamp::class);
            return $stamp->getResult();
        } catch (HandlerFailedException $error) {
            while ($error instanceof HandlerFailedException) {
                $error = $error->getPrevious();
            }
            throw $error;
        } catch (NoHandlerForMessageException) {
            throw new QueryNotRegisteredError($query);
        }
    }
}
