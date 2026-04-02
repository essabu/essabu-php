<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

/**
 * API client for managing document resources.
 */
final class DocumentApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/documents';

    /** @return array<string, mixed> */
    public function upload(string $employeeId, string $documentType, string $filePath): array
    {
        return $this->http->postMultipart(self::BASE_PATH, [
            ['name' => 'employeeId', 'contents' => $employeeId],
            ['name' => 'documentType', 'contents' => $documentType],
            ['name' => 'file', 'contents' => fopen($filePath, 'r'), 'filename' => basename($filePath)],
        ]);
    }

    /** @return array<int, array<string, mixed>> */
    public function listByEmployee(string $employeeId): array
    {
        return $this->http->get(self::BASE_PATH . '?employeeId=' . $employeeId);
    }

    public function download(string $documentId): string
    {
        return $this->http->getBytes(self::BASE_PATH . '/' . $documentId . '/download');
    }

    public function delete(string $documentId): void
    {
        $this->http->delete(self::BASE_PATH . '/' . $documentId);
    }

    /** @return array<int, array<string, mixed>> */
    public function getExpiring(int $withinDays): array
    {
        return $this->http->get(self::BASE_PATH . '/expiring?withinDays=' . $withinDays);
    }
}
