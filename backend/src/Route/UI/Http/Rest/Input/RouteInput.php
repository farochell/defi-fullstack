<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Route\UI\Http\Rest\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class RouteInput
{
    #[Assert\NotBlank(message: "Le champ fromStationId est obligatoire")]
    public string $fromStationId;

    #[Assert\NotBlank(message: "Le champ toStationId est obligatoire")]
    public string $toStationId;

    #[Assert\NotBlank(message: "Le champ analyticCode est obligatoire")]
    public string $analyticCode;
}
