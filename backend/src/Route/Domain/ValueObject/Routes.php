<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\ValueObject;

use App\Route\Domain\Entity\Route;
use App\Shared\Domain\Collection;

class Routes extends Collection {
    protected function type(): string {
        return Route::class;
    }
}
