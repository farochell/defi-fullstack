<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType as DoctrineStringType;

abstract class StringType extends DoctrineStringType
{
    protected const string TYPE = 'string';
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool {
        return true;
    }

    public function getName(): string
    {
        return  static::TYPE;
    }
}
