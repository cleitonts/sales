<?php

namespace Panthir\Domain\Product\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Panthir\Domain\Common\Model\CountableTrait;
use Panthir\Infrastructure\Repository\Product\CategoryRepository;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: 'category')]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    use CountableTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue('NONE')]
    private string $id;

    #[ORM\Column]
    private string $name;

    #[OneToMany(mappedBy: 'parent', targetEntity: Category::class)]
    private Collection $children;

    #[ManyToOne(targetEntity: Category::class, inversedBy: 'children')]
    #[JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    private ?Category $parent = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isLastLevel;

    public function __construct(
        protected UuidInterface $uuid
    ) {
        $this->id = $uuid->__toString();
        $this->children = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Category
    {
        $this->name = $name;

        return $this;
    }

    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param ?Category $parent
     */
    public function setParent(?Category $parent): Category
    {
        $this->parent = $parent;

        return $this;
    }

    public function isLastLevel(): bool
    {
        return $this->isLastLevel;
    }

    public function setIsLastLevel(bool $isLastLevel): Category
    {
        $this->isLastLevel = $isLastLevel;

        return $this;
    }

    /**
     * @return $this
     */
    public function addChildren(Category $category): self
    {
        if (!$this->children->contains($category)) {
            $category->setParent($this);
            $this->children->add($category);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeChildren(Category $category): self
    {
        if ($this->children->contains($category)) {
            $this->children->removeElement($category);
        }

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }
}
