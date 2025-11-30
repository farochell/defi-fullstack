<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);


namespace App\Shared\Domain\Bus\Query;

use RuntimeException;

final class QueryNotRegisteredError extends RuntimeException
{
    public function __construct(Query $query)
    {
        $queryClass = $query::class;
        parent::__construct("The query <$queryClass> hasn't a query handler associated");
    }
}
