<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 */
class Brand
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Car", mappedBy="brands")
     */
    private $car;

    public function __construct()
    {
        $this->car = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, car>
     */
    public function getCar(): Collection
    {
        return $this->car;
    }

    public function addCar(car $car): self
    {
        if (!$this->car->contains($car)) {
            $this->car[] = $car;
            $car->setBrands($this);
        }

        return $this;
    }

    public function removeCar(car $car): self
    {
        if ($this->car->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getBrands() === $this) {
                $car->setBrands(null);
            }
        }

        return $this;
    }
}
