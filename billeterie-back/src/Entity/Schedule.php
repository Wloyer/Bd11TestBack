<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $idUser = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'schedule')]
    private Collection $idEvent;

    public function __construct()
    {
        $this->idEvent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getIdEvent(): Collection
    {
        return $this->idEvent;
    }

    public function addIdEvent(Event $idEvent): static
    {
        if (!$this->idEvent->contains($idEvent)) {
            $this->idEvent->add($idEvent);
            $idEvent->setSchedule($this);
        }

        return $this;
    }

    public function removeIdEvent(Event $idEvent): static
    {
        if ($this->idEvent->removeElement($idEvent)) {
            // set the owning side to null (unless already changed)
            if ($idEvent->getSchedule() === $this) {
                $idEvent->setSchedule(null);
            }
        }

        return $this;
    }
}
