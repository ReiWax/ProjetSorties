<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $dateTimeStartAt;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $duration;

    #[ORM\Column(type: 'datetime')]
    private $dateLimitRegistrationAt;

    #[ORM\Column(type: 'integer')]
    private $nbMaxRegistration;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'event')]
    private $users;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'organizer')]
    #[ORM\JoinColumn(nullable: false)]
    private $organizer;

    #[ORM\ManyToOne(targetEntity: Adress::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $adress;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $location;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $state;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getDateTimeStartAt(): ?\DateTimeInterface
    {
        return $this->dateTimeStartAt;
    }

    public function setDateTimeStartAt(\DateTimeInterface $dateTimeStartAt): self
    {
        $this->dateTimeStartAt = $dateTimeStartAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateLimitRegistrationAt(): ?\DateTimeInterface
    {
        return $this->dateLimitRegistrationAt;
    }

    public function setDateLimitRegistrationAt(\DateTimeInterface $dateLimitRegistrationAt): self
    {
        $this->dateLimitRegistrationAt = $dateLimitRegistrationAt;

        return $this;
    }

    public function getNbMaxRegistration(): ?int
    {
        return $this->nbMaxRegistration;
    }

    public function setNbMaxRegistration(int $nbMaxRegistration): self
    {
        $this->nbMaxRegistration = $nbMaxRegistration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addEvent($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeEvent($this);
        }

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }
}
