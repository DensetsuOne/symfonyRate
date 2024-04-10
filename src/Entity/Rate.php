<?php

namespace App\Entity;

use App\Repository\RateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RateRepository::class)]
#[ORM\Table(name: 'rate')]
class Rate
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $numCode = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $charCode = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nominal = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $value = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $vunitRate = null;

    public function getVunitRate(): ?int
    {
        return $this->vunitRate;
    }

    public function setVunitRate(?int $vunitRate): void
    {
        $this->vunitRate = $vunitRate;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): void
    {
        $this->value = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getNominal(): ?int
    {
        return $this->nominal;
    }

    public function setNominal(?int $nominal): void
    {
        $this->nominal = $nominal;
    }

    public function getCharCode(): ?string
    {
        return $this->charCode;
    }

    public function setCharCode(?string $charCode): void
    {
        $this->charCode = $charCode;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCode(): ?string
    {
        return $this->numCode;
    }

    public function setNumCode(?string $numCode): void
    {
        $this->numCode = $numCode;
    }
}