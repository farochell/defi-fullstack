<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Application\CalculateRoute;

use App\Shared\Domain\Bus\Command\Command;

class CalculateRouteCommand implements Command {
    public function __construct(
        public string $fromStationId,
        public string $toStationId,
        public string $analyticCode
    ){}
}
