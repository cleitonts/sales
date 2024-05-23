<?php

namespace Panthir\Domain\Product\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Panthir\Domain\Common\Model\CountableTrait;
use Panthir\Infrastructure\Repository\Product\ProductRepository;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: 'product')]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    use CountableTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue('NONE')]
    private string $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private float $value;

    #[ManyToOne(targetEntity: Category::class)]
    #[JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private Category $category;

    #[ManyToOne(targetEntity: Brand::class, inversedBy: 'products')]
    #[JoinColumn(name: 'brand_id', referencedColumnName: 'id')]
    private Brand $brand;

    public function __construct(
        protected UuidInterface $uuid
    ) {
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

    public function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): Product
    {
        $this->category = $category;

        return $this;
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): Product
    {
        $this->brand = $brand;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): Product
    {
        $this->value = $value;

        return $this;
    }
}
