<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Controller;

use App\Route\UI\Http\Rest\Formatter\ErrorFormatterTrait;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetAnalyticDistancesController
{
    use ErrorFormatterTrait;
}
