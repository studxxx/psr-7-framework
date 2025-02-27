<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Content
{
    /**
     * @ORM\Column(type="text")
     */
    private ?string $short;
    /**
     * @ORM\Column(type="text")
     */
    private ?string $full;

    public function __construct(?string $short, string $full)
    {
        $this->short = $short;
        $this->full = $full;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function getFull(): string
    {
        return $this->full;
    }
}
