<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\Infrastructure\Doctrine\Mapping;

use App\Route\Domain\Entity\Station;
use App\Route\Domain\ValueObject\RouteId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'route')]
class DoctrineRoute {
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'route_id', unique: true)]
    public RouteId $id;

    #[ORM\Column(type: 'string')]
    public string $fromStation;

    #[ORM\Column(type: 'string')]
    public string $toStation;

    #[ORM\Column(type: 'string')]
    public string $analyticCode;

    #[ORM\Column(type: 'float')]
    public float $distanceKm;

    /**
     * @var array<Station>
     */
    #[ORM\Column(type: 'json')]
    public array $path;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $createdAt;
}
