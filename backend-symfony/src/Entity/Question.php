<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ApiResource]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Le texte de la question est obligatoire.')]
    #[Assert\Length(
        max: 500,
        maxMessage: 'Le texte de la question ne peut pas dépasser 500 caractères.'
    )]
    private ?string $questionText = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le type de média est obligatoire.')]
    #[Assert\Choice(
        choices: ['image', 'video', 'audio', 'text'],
        message: "Le type de média doit être l'une des valeurs suivantes : image, video, audio ou text."
    )]
    private ?string $mediaType = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(
        message: "L'URL du média doit être valide.",
        requireTld: false
    )]
    private ?string $mediaUrl = null;

    #[ORM\Column]
    #[Assert\Type(type: 'bool')]
    private ?bool $isRoot = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Questionnaire $questionnaire = null;

    /**
     * @var Collection<int, Choice>
     */
    #[ORM\OneToMany(targetEntity: Choice::class, mappedBy: 'question', orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $choices;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionText(): ?string
    {
        return $this->questionText;
    }

    public function setQuestionText(string $questionText): static
    {
        $this->questionText = $questionText;

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): static
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getMediaUrl(): ?string
    {
        return $this->mediaUrl;
    }

    public function setMediaUrl(?string $mediaUrl): static
    {
        $this->mediaUrl = $mediaUrl;

        return $this;
    }

    public function isRoot(): ?bool
    {
        return $this->isRoot;
    }

    public function setIsRoot(bool $isRoot): static
    {
        $this->isRoot = $isRoot;

        return $this;
    }

    public function getQuestionnaire(): ?Questionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(?Questionnaire $questionnaire): static
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    /**
     * @return Collection<int, Choice>
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice): static
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
            $choice->setquestion($this);
        }

        return $this;
    }

    public function removeChoice(Choice $choice): static
    {
        if ($this->choices->removeElement($choice)) {
            // set the owning side to null (unless already changed)
            if ($choice->getquestion() === $this) {
                $choice->setquestion(null);
            }
        }

        return $this;
    }
}
