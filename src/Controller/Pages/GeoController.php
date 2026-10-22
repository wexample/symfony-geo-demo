<?php

namespace Wexample\SymfonyGeoDemo\Controller\Pages;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyGeo\Repository\CountryRepository;
use Wexample\SymfonyGeo\Service\GeocodingService;
use Wexample\SymfonyGeoDemo\Repository\DemoPlaceRepository;
use Wexample\SymfonyGeoDemo\Traits\SymfonyGeoDemoBundleClassTrait;
use Wexample\SymfonyHelpers\Helper\VariableHelper;
use Wexample\SymfonyLoader\Controller\AbstractPagesController;

/**
 * Addresses an app holds, placed on a map (symfony-geo-ds), and the road
 * joining some of them.
 */
#[Route(path: '/geo/', name: 'geo_demo_')]
final class GeoController extends AbstractPagesController
{
    use SymfonyGeoDemoBundleClassTrait;

    final public const string ROUTE_INDEX = VariableHelper::INDEX;

    final public const string ROUTE_ROUTE = 'route';

    #[Route(path: '', name: self::ROUTE_INDEX)]
    public function index(
        DemoPlaceRepository $placeRepository,
        CountryRepository $countryRepository,
        GeocodingService $geocodingService,
    ): Response {
        return $this->renderPage(self::ROUTE_INDEX, [
            'places' => array_values($placeRepository->findOrCreateDemoPlaces($countryRepository)),
            'geocoder_available' => $geocodingService->isAvailable(),
        ]);
    }

    #[Route(path: self::ROUTE_ROUTE, name: self::ROUTE_ROUTE)]
    public function route(
        DemoPlaceRepository $placeRepository,
        CountryRepository $countryRepository,
    ): Response {
        $places = $placeRepository->findOrCreateDemoPlaces($countryRepository);

        return $this->renderPage(self::ROUTE_ROUTE, [
            'stops' => array_map(
                static fn (string $slug) => $places[$slug],
                DemoPlaceRepository::ROUTE
            ),
        ]);
    }
}
