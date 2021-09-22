<?php

namespace App\Entity;

use JsonSerializable;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements JsonSerializable
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
    private $Username;

    /**
     * @ORM\Column(type="integer")
     */
    private $GameId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Role;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Lane;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

        return $this;
    }

    public function getGameId(): ?int
    {
        return $this->GameId;
    }

    public function setGameId(int $GameId): self
    {
        $this->GameId = $GameId;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(string $Role): self
    {
        $this->Role = $Role;

        return $this;
    }

    public function getLane(): ?string
    {
        return $this->Lane;
    }

    public function setLane(string $Lane): self
    {
        $this->Lane = $Lane;

        return $this;
    }

    // L'interface demande à avoir la méthode jsonSerialize() qui va proprement
    // linéariser votre entité pour retourner sous forme de JsonResponse
    // Il suffit de return un tableau avec les champs à serialize et c'est ok
    // Tout champ manquant ici ne sera pas retourné au front
    public function jsonSerialize(){
        return array(
                'id' => $this->id,
                'GameId' => $this->GameId,
                'Role' => $this->Role,
                'Lane' => $this->Lane,
                'Username' => $this->Username,
            );
    }
}
