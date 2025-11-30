<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\Exception;

use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Exception\ErrorCode;

class StationNotFoundException extends EntityNotFoundException
{
    private string $shortName;

    public function __construct(string $shortName)
    {
        parent::__construct("Station inconnue : $shortName");
        $this->shortName = $shortName;
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::STATION_NOT_FOUND;
    }

    /**
     * @return string[]
     */
    public function getDetails(): array
    {
        return [$this->shortName];
    }

}
