<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[ORM\Entity(repositoryClass: QuizzRepository::class)]
#[Vich\Uploadable]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Theme = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $Difficulty = null;

    #[ORM\ManyToOne(inversedBy: 'createdquizz')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Author = null;

    #[ORM\OneToMany(mappedBy: 'quizz', cascade: ["persist", "remove"], targetEntity: Questions::class, orphanRemoval: true)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'quizz', targetEntity: Play::class, orphanRemoval: true)]
    private Collection $played;

    #[ORM\Column]
    private ?bool $Hide = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[Vich\UploadableField(mapping: 'quizz_image', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;
    
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $imageName = null;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->played = new ArrayCollection();
        $this->setHide(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->Theme;
    }

    public function setTheme(string $Theme): self
    {
        $this->Theme = $Theme;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->Difficulty;
    }

    public function setDifficulty(int $Difficulty): self
    {
        $this->Difficulty = $Difficulty;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?User $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    /**
     * @return Collection<int, Questions>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Questions $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuizz($this);
        }

        return $this;
    }

    public function removeQuestion(Questions $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuizz() === $this) {
                $question->setQuizz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Play>
     */
    public function getPlayed(): Collection
    {
        return $this->played;
    }

    public function addPlayed(Play $played): self
    {
        if (!$this->played->contains($played)) {
            $this->played->add($played);
            $played->setQuizz($this);
        }

        return $this;
    }

    public function removePlayed(Play $played): self
    {
        if ($this->played->removeElement($played)) {
            // set the owning side to null (unless already changed)
            if ($played->getQuizz() === $this) {
                $played->setQuizz(null);
            }
        }

        return $this;
    }

    public function isHide(): ?bool
    {
        return $this->Hide;
    }

    public function setHide(bool $Hide): self
    {
        $this->Hide = $Hide;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $image = null): void
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
}
