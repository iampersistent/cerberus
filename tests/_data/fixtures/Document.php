<?php
declare(strict_types = 1);

namespace Test;

class Document
{
    protected $clientName;
    protected $documentId;
    protected $documentName;
    protected $documentOwner;

    public function __construct(int $documentId, string $documentName, string $clientName, string $documentOwner)
    {
        $this->documentId = $documentId;
        $this->documentName = $documentName;
        $this->clientName = $clientName;
        $this->documentOwner = $documentOwner;
    }

    public function getDocumentId(): int
    {
        return $this->documentId;
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
}