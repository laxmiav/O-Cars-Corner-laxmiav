<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please entre your Car's")
     */
    private $model;

    /**
     * @ORM\Column(type="date")
     */
    private $releaseYear;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="car")
     */
    private $brands;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getReleaseYear(): ?\DateTimeInterface
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(\DateTimeInterface $releaseYear): self
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    public function getBrands(): ?Brand
    {
        return $this->brands;
    }

    public function setBrands(?Brand $brands): self
    {
        $this->brands = $brands;

        return $this;
    }
}
