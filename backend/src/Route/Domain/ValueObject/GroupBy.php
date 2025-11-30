<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Domain\ValueObject;
enum GroupBy: string
{
    case DAY = 'day';
    case MONTH = 'month';
    case YEAR = 'year';
    case NONE = 'none';
}
