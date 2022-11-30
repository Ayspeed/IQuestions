<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $Pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $ThemePref = null;

    #[ORM\Column]
    private ?int $Age = null;

    #[ORM\OneToMany(mappedBy: 'Author', targetEntity: Quizz::class, orphanRemoval: true)]
    private Collection $createdquizz;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Play::class, orphanRemoval: true)]
    private Collection $playedquizz;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Answer::class, orphanRemoval: true)]
    private Collection $answers;

    #[ORM\Column]
    private ?bool $Hide = null;

    public function __construct()
    {
        $this->createdquizz = new ArrayCollection();
        $this->playedquizz = new ArrayCollection();
        $this->answers = new ArrayCollection();
        
        $this
        ->setHide(false)
        ->setRoles(['ROLE_USER']);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if ($this->getId() == 1){
            $this->setRoles(['ROLE_ADMIN']);
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->Pseudo;
    }

    public function setPseudo(string $Pseudo): self
    {
        $this->Pseudo = $Pseudo;

        return $this;
    }
    
    public function getThemePref(): ?string
    {
        return $this->ThemePref;
    }

    public function setThemePref(string $ThemePref): self
    {
        $this->ThemePref = $ThemePref;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->Age;
    }

    public function setAge(int $Age): self
    {
        $this->Age = $Age;

        return $this;
    }

    /**
     * @return Collection<int, Quizz>
     */
    public function getCreatedquizz(): Collection
    {
        return $this->createdquizz;
    }

    public function addCreatedquizz(Quizz $createdquizz): self
    {
        if (!$this->createdquizz->contains($createdquizz)) {
            $this->createdquizz->add($createdquizz);
            $createdquizz->setAuthor($this);
        }

        return $this;
    }

    public function removeCreatedquizz(Quizz $createdquizz): self
    {
        if ($this->createdquizz->removeElement($createdquizz)) {
            // set the owning side to null (unless already changed)
            if ($createdquizz->getAuthor() === $this) {
                $createdquizz->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Play>
     */
    public function getPlayedquizz(): Collection
    {
        return $this->playedquizz;
    }

    public function addPlayedquizz(Play $playedquizz): self
    {
        if (!$this->playedquizz->contains($playedquizz)) {
            $this->playedquizz->add($playedquizz);
            $playedquizz->setPlayer($this);
        }

        return $this;
    }

    public function removePlayedquizz(Play $playedquizz): self
    {
        if ($this->playedquizz->removeElement($playedquizz)) {
            // set the owning side to null (unless already changed)
            if ($playedquizz->getPlayer() === $this) {
                $playedquizz->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setPlayer($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getPlayer() === $this) {
                $answer->setPlayer(null);
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

    public function __toString()
    {
        return $this->getPseudo();
    }
}
