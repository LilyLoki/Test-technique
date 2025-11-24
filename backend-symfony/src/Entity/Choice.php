<?php

namespace App\Entity;

use App\Repository\ChoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChoiceRepository::class)]
class Choice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $choiceText = null;

    #[ORM\Column]
    private ?int $displayOrder = null;

    #[ORM\ManyToOne(targetEntity: Question::class,inversedBy: 'choices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $questionId = null;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    private ?Question $nextQuestionId = null;

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

    public function getQuestionId(): ?Question
    {
        return $this->questionId;
    }

    public function setQuestionId(?Question $questionId): static
    {
        $this->questionId = $questionId;

        return $this;
    }

    public function getNextQuestionId(): ?Question
    {
        return $this->nextQuestionId;
    }

    public function setNextQuestionId(?Question $nextQuestionId): static
    {
        $this->nextQuestionId = $nextQuestionId;

        return $this;
    }
}
