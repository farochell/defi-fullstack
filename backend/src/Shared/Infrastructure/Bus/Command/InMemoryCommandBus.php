<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Command\CommandNotRegisteredError;
use App\Shared\Domain\Bus\Command\CommandResponse;
use App\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use App\Shared\Infrastructure\Bus\Middleware\LoggerMiddleware;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class InMemoryCommandBus implements CommandBus {
    private MessageBus $messageBus;

    /**
     * @param iterable<callable> $commandHandlers Les handlers de commandes
     */
    public function __construct(iterable $commandHandlers, LoggerInterface $logger) {
        $this->messageBus = new MessageBus([
            new HandleMessageMiddleware(
                new HandlersLocator(CallableFirstParameterExtractor::forCallables($commandHandlers))
            ),
            new LoggerMiddleware($logger)
        ]);
    }

    public function dispatch(Command $command): CommandResponse
    {
        try {
            $stamps = $this->messageBus->dispatch($command)->last(HandledStamp::class);

            return $stamps->getResult();
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                $e = $e->getPrevious();
            }
            throw $e;
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredError($command);
        }
    }
}
