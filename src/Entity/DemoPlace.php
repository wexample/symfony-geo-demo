<?php

namespace Wexample\SymfonyGeoDemo\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Wexample\SymfonyGeo\Entity\Traits\HasPostalAddressTrait;
use Wexample\SymfonyGeo\Interface\GeoLocatedInterface;
use Wexample\SymfonyGeo\Interface\PostalAddressInterface;
use Wexample\SymfonyGeoDemo\Repository\DemoPlaceRepository;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;

/**
 * A named place with a postal address and the point it sits on: what an app
 * holding addresses — customers, shops, deliveries — puts on a map.
 *
 * Its identity derives from its slug, so the places the pages ask for are
 * made once and found on every visit after.
 */
#[ORM\Entity(repositoryClass: DemoPlaceRepository::class)]
#[ORM\Table(name: 'demo_geo_place')]
#[ORM\UniqueConstraint(columns: ['slug'])]
class DemoPlace extends AbstractEntity implements PostalAddressInterface, GeoLocatedInterface
{
    use HasPostalAddressTrait;

    public const string ID_NAMESPACE = '5d0b7c1e-8f3a-5a42-b6e9-2c4f81d7a93e';

    #[ORM\Column(type: Types::STRING, length: 128)]
    protected string $slug;

    #[ORM\Column(type: Types::STRING, length: 255)]
    protected string $name;

    public function __construct(
        string $slug,
        string $name
    ) {
        parent::__construct();

        $this->slug = $slug;
        $this->name = $name;

        $this->setId(self::idFor($slug));
    }

    public static function idFor(string $slug): Uuid
    {
        return Uuid::v5(Uuid::fromString(self::ID_NAMESPACE), $slug);
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
