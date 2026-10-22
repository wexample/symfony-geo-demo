<?php

namespace Wexample\SymfonyGeoDemo\Entity\Traits\Manipulator;

use Wexample\SymfonyGeoDemo\Entity\DemoPlace;
use Wexample\SymfonyHelpers\Entity\Traits\Manipulator\EntityManipulatorTrait;

trait DemoPlaceEntityManipulatorTrait
{
    use EntityManipulatorTrait;

    public static function getEntityClassName(): string
    {
        return DemoPlace::class;
    }
}
