<?php

namespace Wexample\SymfonyGeoDemo\Repository;

use Wexample\SymfonyGeo\Class\GeoPoint;
use Wexample\SymfonyGeo\Repository\CountryRepository;
use Wexample\SymfonyGeoDemo\Entity\DemoPlace;
use Wexample\SymfonyGeoDemo\Entity\Traits\Manipulator\DemoPlaceEntityManipulatorTrait;
use Wexample\SymfonyHelpers\Repository\AbstractRepository;

/**
 * @method DemoPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoPlace[]    findAll()
 */
class DemoPlaceRepository extends AbstractRepository
{
    use DemoPlaceEntityManipulatorTrait;

    /**
     * Public landmarks, so that no one's address ends up in a demo. The
     * country is looked up in the country table, and stays empty where the
     * table was not seeded.
     */
    private const array PLACES = [
        'grand-place' => ['Grand-Place', 'Grand-Place', '1000', 'Bruxelles', 'BE', 50.8467, 4.3525],
        'atomium' => ['Atomium', "Place de l'Atomium 1", '1020', 'Bruxelles', 'BE', 50.8949, 4.3415],
        'gravensteen' => ['Gravensteen', 'Sint-Veerleplein 11', '9000', 'Gent', 'BE', 51.0573, 3.7208],
        'markt-brugge' => ['Markt', 'Markt', '8000', 'Brugge', 'BE', 51.2089, 3.2242],
        'grand-place-lille' => ['Grand-Place de Lille', 'Place du Général de Gaulle', '59800', 'Lille', 'FR', 50.6366, 3.0635],
        'tour-eiffel' => ['Tour Eiffel', 'Champ de Mars, 5 avenue Anatole France', '75007', 'Paris', 'FR', 48.8584, 2.2945],
    ];

    /**
     * The places a road goes through, in order, on the route page.
     */
    public const array ROUTE = ['grand-place', 'gravensteen', 'markt-brugge'];

    /**
     * A demo has no fixtures: the places are made the first time a page is
     * opened, and found on every visit after.
     *
     * @return array<string, DemoPlace> by slug
     */
    public function findOrCreateDemoPlaces(CountryRepository $countryRepository): array
    {
        $places = [];

        foreach (self::PLACES as $slug => [$name, $street, $postCode, $city, $countryCode, $latitude, $longitude]) {
            $place = $this->find(DemoPlace::idFor($slug));

            if (null === $place) {
                $place = (new DemoPlace($slug, $name))
                    ->setPostalAddress($street)
                    ->setPostCode($postCode)
                    ->setCity($city)
                    ->setCountry($countryRepository->findByIsoAlpha2Code($countryCode))
                    ->setGeoPoint(new GeoPoint($latitude, $longitude));
                $this->getEntityManager()->persist($place);
            }

            $places[$slug] = $place;
        }

        $this->getEntityManager()->flush();

        return $places;
    }
}
