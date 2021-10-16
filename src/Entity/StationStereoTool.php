<?php

declare(strict_types=1);

namespace Plugin\StereoToolPlugin\Entity;

use App\Entity\Attributes;
use App\Entity\Interfaces;
use App\Entity\Station;
use App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[
    ORM\Entity,
    ORM\Table(name: 'plugin_station_stereo_tool'),
    Attributes\Auditable
]
class StationStereoTool implements
    Stringable,
    Interfaces\StationCloneAwareInterface,
    Interfaces\IdentifiableEntityInterface
{
    use Traits\HasAutoIncrementId;
    use Traits\TruncateStrings;

    #[ORM\Column(nullable: false)]
    protected int $station_id;

    #[ORM\OneToOne(targetEntity: Station::class)]
    #[ORM\JoinColumn(name: 'station_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    protected Station $station;

    #[ORM\Column]
    protected bool $enable_stereo_tool = false;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $license_key = null;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $stereo_tool_configuration = null;

    public function __construct(Station $station)
    {
        $this->station = $station;
    }

    public function getStation(): Station
    {
        return $this->station;
    }

    public function setStation(Station $station): void
    {
        $this->station = $station;
    }

    public function getEnableStereoTool(): bool
    {
        return $this->enable_stereo_tool;
    }

    public function setEnableStereoTool(bool $enableStereoTool): void
    {
        $this->enable_stereo_tool = $enableStereoTool;
    }

    public function getLicenseKey(): ?string
    {
        return $this->license_key;
    }

    public function setLicenseKey(?string $licenseKey): void
    {
        $licenseKey = trim($licenseKey ?? '');
        $licenseKey = (!empty($licenseKey)) ? $licenseKey : null;

        $this->license_key = $this->truncateNullableString($licenseKey);
    }

    public function getStereoToolConfiguration(): ?string
    {
        return $this->stereo_tool_configuration;
    }

    public function setStereoToolConfiguration(?string $stereoToolConfiguration): void
    {
        $this->stereo_tool_configuration = $stereoToolConfiguration;
    }

    public function __toString(): string
    {
        return $this->getStation() . ' Stereo Tool enabled: ' . $this->getEnableStereoTool();
    }
}
