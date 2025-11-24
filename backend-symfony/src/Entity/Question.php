<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $questionText = null;

    #[ORM\Column(length: 255)]
    private ?string $mediaType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mediaUrl = null;

    #[ORM\Column]
    private ?bool $isRoot = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questionnaire $questionnaireId = null;

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

    public function getQuestionnaireId(): ?Questionnaire
    {
        return $this->questionnaireId;
    }

    public function setQuestionnaireId(?Questionnaire $questionnaireId): static
    {
        $this->questionnaireId = $questionnaireId;

        return $this;
    }
}
