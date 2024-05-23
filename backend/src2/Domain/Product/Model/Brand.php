<?php

namespace Panthir\Domain\Product\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Panthir\Domain\Common\Model\CountableTrait;
use Panthir\Infrastructure\Repository\Product\BrandRepository;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: 'brand')]
#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    use CountableTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue('NONE')]
    private string $id;

    #[ORM\Column]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Product::class, cascade: ['persist'])]
    private Collection $products;

    public function __construct(
        protected UuidInterface $uuid
    ) {
        $this->products = new ArrayCollection();
        $this->id = $uuid->__toString();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Brand
    {
        $this->name = $name;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function addProducts(Product $address): self
    {
        if (!$this->products->contains($address)) {
            $address->setBrand($this);
            $this->products->add($address);
        }

        return $this;
    }

    public function removeProducts(Product $address): self
    {
        if ($this->products->contains($address)) {
            $this->products->removeElement($address);
        }

        return $this;
    }
}
