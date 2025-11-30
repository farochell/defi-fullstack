<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Shared\Domain\Exception;

enum ErrorCode : string
{
    case INVALID_REQUEST = 'INVALID_REQUEST';
    case STATION_NOT_FOUND = 'STATION_NOT_FOUND';
    case ROUTE_NOT_FOUND = 'ROUTE_NOT_FOUND';
    case NETWORK_DISCONNECTED = 'NETWORK_DISCONNECTED';
    case ANALYTIC_INVALID_PERIOD = 'ANALYTIC_INVALID_PERIOD';
    case UNKNOWN_ERROR = 'UNKNOWN_ERROR';
    case MISSING_PARAMETERS = 'MISSING_PARAMETERS';
}
