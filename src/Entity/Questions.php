<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
#[Vich\Uploadable]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Wording = null;

    #[ORM\Column]
    private array $Answers = [];

    #[Vich\UploadableField(mapping: 'question_image', fileNameProperty: 'imageNameQuestion')]
    private ?File $imagesFileQuestion = null;

   
    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\IsNull]
    private ?string $imageNameQuestion = null;

    #[ORM\Column(length: 255)]
    private ?string $CorrectAnswer = null;

    #[ORM\ManyToOne(targetEntity: Quizz::class,cascade : ["persist", "remove"], inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quizz $quizz = null;

    #[ORM\OneToMany(mappedBy: 'questions', targetEntity: Answer::class, orphanRemoval: true)]
    private Collection $playeranswers;

    public function __construct()
    {
        $this->playeranswers = new ArrayCollection();
    }
    private $questions;
    
    public function getquestions(){
        return $this->questions;
    }
    public function setQuestions($questions){
        $this->questions=$questions;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWording(): ?string
    {
        return $this->Wording;
    }

    public function setWording(string $Wording): self
    {
        $this->Wording = $Wording;

        return $this;
    }

    public function getAnswers(): ?array
    {
        return $this->Answers;
    }

    public function setAnswers(array $answers): self
    {
        $this->Answers = $answers;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->CorrectAnswer;
    }

    public function setCorrectAnswer(string $CorrectAnswer): self
    {
        $this->CorrectAnswer = $CorrectAnswer;

        return $this;
    }

    public function getQuizz(): ?Quizz
    {
        return $this->quizz;
    }

    public function setQuizz(?Quizz $quizz): self
    {
        $this->quizz = $quizz;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getPlayeranswers(): Collection
    {
        return $this->playeranswers;
    }

    public function addPlayeranswer(Answer $playeranswer): self
    {
        if (!$this->playeranswers->contains($playeranswer)) {
            $this->playeranswers->add($playeranswer);
            $playeranswer->setQuestions($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getWording();
    }
    public function removePlayeranswer(Answer $playeranswer): self
    {
        if ($this->playeranswers->removeElement($playeranswer)) {
            // set the owning side to null (unless already changed)
            if ($playeranswer->getQuestions() === $this) {
                $playeranswer->setQuestions(null);
            }
        }

        return $this;
    }
    /**
    * 
    * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imagesFileQuestion
    */
   public function setimagesFileQuestion(?File $image = null): void
   {
       $this->imagesFileQuestion = $image;

       if ($image) {
           // It is required that at least one field changes if you are using doctrine
           // otherwise the event listeners won't be called and the file is lost
           $this->updatedAt = new \DateTime('now');
       }
   }

   public function getimagesFileQuestion(): ?File
   {
       return $this->imagesFileQuestion;
   }

   public function setimageNameQuestion(?string $imageNameQuestion): void
   {
       $this->imageNameQuestion = $imageNameQuestion;
   }

   public function getimageNameQuestion(): ?string
   {
       return $this->imageNameQuestion;
   }
}
