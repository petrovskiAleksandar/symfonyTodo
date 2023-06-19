<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Entity()
 */
class Todo
{
    /** 
     *@ORM\Id()
     *@ORM\GeneratedValue()
     *@ORM\Column(type="integer")
     */
    private int $id;

    /** 
     * @ORM\Column(type="string", length=255)
     */
    private string $name = '';

    /** 
     * @ORM\Column(type="string", length=255)
     */
    private string $description = '';

    /** 
     * @ORM\Column(type="boolean")
     */
    private bool $status = false;

    /** 
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdOn = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }
}
