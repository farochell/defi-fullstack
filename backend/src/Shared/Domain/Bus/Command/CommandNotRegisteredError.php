<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);


namespace App\Shared\Domain\Bus\Command;

use RuntimeException;

final class CommandNotRegisteredError extends RuntimeException
{
    public function __construct(Command $command)
    {
        $commandClass = $command::class;
        parent::__construct("The command <$commandClass> hasn't a command handler associated");
    }
}
