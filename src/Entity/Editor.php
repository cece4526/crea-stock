<?php

namespace App\Entity;

use App\Repository\EditorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditorRepository::class)]
class Editor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;


    #[ORM\OneToMany(mappedBy: 'editor', targetEntity: product::class, orphanRemoval: true)]
    private Collection $product;

    #[ORM\OneToMany(mappedBy: 'editor', targetEntity: Author::class)]
    private Collection $authors;


    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->authors = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return Collection<int, product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(product $product): static
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
            $product->setEditor($this);
        }

        return $this;
    }

    public function removeProduct(product $product): static
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getEditor() === $this) {
                $product->setEditor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->setEditor($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        if ($this->authors->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getEditor() === $this) {
                $author->setEditor(null);
            }
        }

        return $this;
    }

}
