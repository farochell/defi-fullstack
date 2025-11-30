<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);


namespace App\Shared\Infrastructure\Bus\Middleware;

use App\Shared\Domain\Utils;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;

readonly class LoggerMiddleware implements MiddlewareInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if ($envelope->last(HandledStamp::class) instanceof StampInterface) {
            $message = $envelope->getMessage();
            $this->logger->info(Utils::className($message) . ' was handled');
        }
        return $stack->next()->handle($envelope, $stack);
    }
}
