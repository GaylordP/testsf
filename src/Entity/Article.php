<?php

namespace App\Entity;

use App\Annotation\Slugger;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get", "delete"},
 *     normalizationContext={
 *         "skip_null_values"=false,
 *         "groups"={"articles_read"}
 *     },
 *     denormalizationContext={
 *         "groups"={"articles_write"}
 *     },
 *     attributes={
 *         "order"={"createdAt":"asc"},
 *         "pagination_enabled"=false,
 *     }
 * )
 */
class Article
{
    /**
     * @ApiProperty(identifier=false)
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({
     *     "articles_read",
     * })
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Groups({
     *     "articles_read",
     *     "articles_write"
     * })
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Groups({
     *     "articles_read",
     *     "articles_write"
     * })
     */
    private $leading;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Groups({
     *     "articles_read",
     *     "articles_write"
     * })
     */
    private $body;

    /**
     * @var string
     *
     * @ApiProperty(identifier=true)
     * @ORM\Column(type="string", unique=true)
     * @Slugger(field="title")
     * @Groups({
     *     "articles_read",
     * })
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Groups({
     *     "articles_read",
     * })
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Groups({
     *     "articles_read",
     *     "articles_write"
     * })
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getLeading(): ?string
    {
        return $this->leading;
    }

    public function setLeading(?string $leading)
    {
        $this->leading = $leading;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body)
    {
        $this->body = $body;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug)
    {
        $this->slug = $slug;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $date): void
    {
        $this->createdAt = $date;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $user): void
    {
        $this->createdBy = $user;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTime $date): void
    {
        $this->deletedAt = $date;
    }
}
