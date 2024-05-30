<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ServiceRepository;

#[ORM\Table(name: 'services')]
#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $target = null;

    #[ORM\Column]
    private ?int $port = null;

    #[ORM\Column]
    private ?int $max_timeout = null;

    #[ORM\Column(nullable: true)]
    private ?int $http_accept_code = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $last_check_time = null;

    #[ORM\Column(length: 255)]
    private ?string $last_status = null;

    /**
     * @var array<int>
     */
    #[ORM\Column]
    private array $user_ids = [];

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port): static
    {
        $this->port = $port;

        return $this;
    }

    public function getMaxTimeout(): ?int
    {
        return $this->max_timeout;
    }

    public function setMaxTimeout(int $max_timeout): static
    {
        $this->max_timeout = $max_timeout;

        return $this;
    }

    public function getHttpAcceptCode(): ?int
    {
        return $this->http_accept_code;
    }

    public function setHttpAcceptCode(?int $http_accept_code): static
    {
        $this->http_accept_code = $http_accept_code;

        return $this;
    }

    public function getLastCheckTime(): ?\DateTimeInterface
    {
        return $this->last_check_time;
    }

    public function setLastCheckTime(\DateTimeInterface $last_check_time): static
    {
        $this->last_check_time = $last_check_time;

        return $this;
    }

    public function getLastStatus(): ?string
    {
        return $this->last_status;
    }

    public function setLastStatus(string $last_status): static
    {
        $this->last_status = $last_status;

        return $this;
    }

    /**
     * @return array<int>
     */
    public function getUserIds(): array
    {
        return $this->user_ids;
    }

    /**
     * @param array<int> $user_ids
     */
    public function setUserIds(array $user_ids): static
    {
        $this->user_ids = $user_ids;

        return $this;
    }
}
