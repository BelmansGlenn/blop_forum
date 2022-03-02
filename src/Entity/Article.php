<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{

    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="article")
     */
    private $answers;

    /**
     * @ORM\OneToMany(targetEntity=UpVote::class, mappedBy="article")
     */
    private $upVotes;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->upVotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setArticle($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getArticle() === $this) {
                $answer->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UpVote[]
     */
    public function getUpVotes(): Collection
    {
        return $this->upVotes;
    }

    public function addUpVote(UpVote $upVote): self
    {
        if (!$this->upVotes->contains($upVote)) {
            $this->upVotes[] = $upVote;
            $upVote->setArticle($this);
        }

        return $this;
    }

    public function removeUpVote(UpVote $upVote): self
    {
        if ($this->upVotes->removeElement($upVote)) {
            // set the owning side to null (unless already changed)
            if ($upVote->getArticle() === $this) {
                $upVote->setArticle(null);
            }
        }

        return $this;
    }
}
