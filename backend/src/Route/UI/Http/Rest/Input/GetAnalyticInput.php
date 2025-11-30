<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Input;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiProperty;

class GetAnalyticInput {
    #[ApiProperty(
        description: 'Date de début (inclus)'
    )]
    public string $from;

    #[ApiProperty(
        description: 'Date de fin (inclus)',
        schema: ['type' => 'string']
    )]
    public string $to;

    #[ApiProperty(
        description: 'Date de fin (inclus)',
        schema: ['type' => 'string', 'enum' => ['day', 'month', 'year', 'none']]
    )]
    public string $groupBy;
}
