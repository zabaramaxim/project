<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\Column(type: 'string', length: 25)]
    private $tag;

    #[ORM\ManyToOne(targetEntity: ItemCollection::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private $collection;


    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $line;

    #[ORM\Column(type: 'text', nullable: true)]
    private $text;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date;




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

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getCollection(): ?ItemCollection
    {
        return $this->collection;
    }

    public function setCollection(?ItemCollection $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getLine(): ?string
    {
        return $this->line;
    }

    public function setLine(?string $line): self
    {
        $this->line = $line;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
