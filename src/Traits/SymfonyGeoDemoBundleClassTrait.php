<?php

namespace Wexample\SymfonyGeoDemo\Traits;

use Wexample\SymfonyGeoDemo\WexampleSymfonyGeoDemoBundle;
use Wexample\SymfonyHelpers\Traits\BundleClassTrait;

trait SymfonyGeoDemoBundleClassTrait
{
    use BundleClassTrait;

    public static function getBundleClassName(): string
    {
        return WexampleSymfonyGeoDemoBundle::class;
    }
}
