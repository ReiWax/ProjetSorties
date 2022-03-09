<?php

namespace App\Entity;

use App\Repository\CsvFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CsvFileRepository::class)]
class CsvFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $csvFilename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCsvFilename(): ?string
    {
        return $this->csvFilename;
    }

    public function setCsvFilename(string $csvFilename): self
    {
        $this->csvFilename = $csvFilename;

        return $this;
    }
}
