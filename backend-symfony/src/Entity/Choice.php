<?php

namespace App\Entity;

use App\Repository\ChoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: ChoiceRepository::class)]
#[ApiResource]
class Choice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Le texte du choix est obligatoire.")]
    #[Assert\Length(
        max: 500,
        maxMessage: "Le texte du choix ne peut pas dépasser 500 caractères."
    )]
    private ?string $choiceText = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: 'integer')]
    #[Assert\PositiveOrZero]
    private ?int $displayOrder = null;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'choices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Question $question = null;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[Assert\Valid]
    private ?Question $nextQuestion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoiceText(): ?string
    {
        return $this->choiceText;
    }

    public function setChoiceText(string $choiceText): static
    {
        $this->choiceText = $choiceText;

        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): static
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getNextQuestion(): ?Question
    {
        return $this->nextQuestion;
    }

    public function setNextQuestion(?Question $nextQuestion): static
    {
        $this->nextQuestion = $nextQuestion;

        return $this;
    }
}
