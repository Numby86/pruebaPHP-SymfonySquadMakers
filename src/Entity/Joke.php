<?php

namespace App\Entity;

use App\Repository\JokeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JokeRepository::class)]
class Joke
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $joke = null;

    #[ORM\Column(unique:true)]
    private ?int $numberJoke = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoke(): ?string
    {
        return $this->joke;
    }

    public function setJoke(string $joke): self
    {
        $this->joke = $joke;

        return $this;
    }

    public function getNumberJoke(): ?int
    {
        return $this->numberJoke;
    }

    public function setNumberJoke(int $numberJoke): self
    {
        $this->numberJoke = $numberJoke;

        return $this;
    }
}
