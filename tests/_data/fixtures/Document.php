<?php
declare(strict_types = 1);

namespace TestData;

class Document
{
    protected $clientName;
    protected $documentId;
    protected $documentName;
    protected $documentOwner;
    protected $size;
    protected $isPublic;

    public function __construct(
        int $documentId,
        string $documentName,
        string $clientName,
        string $documentOwner,
        int $size = 0,
        bool $isPublic = false
    ) {
        $this->documentId = $documentId;
        $this->documentName = $documentName;
        $this->clientName = $clientName;
        $this->documentOwner = $documentOwner;
        $this->size = $size;
        $this->isPublic = $isPublic;
    }

    public function getDocumentId(): string
    {
        return (string)$this->documentId;
    }

    public function getDocumentName(): string
    {
        return $this->documentName;
    }

    public function getDocumentOwner(): string
    {
        return $this->documentOwner;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): self
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getDocumentSize(): int
    {
        return $this->size;
    }

    public function setDocumentSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getIsPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }
}