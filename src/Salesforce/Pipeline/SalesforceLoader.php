<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Salesforce\Pipeline;

use Pipetic\Bundle\Pipeline\AbstractLoader;
use Pipetic\Bundle\Salesforce\Api\SalesforceClient;

/**
 * Base loader for writing records to Salesforce.
 *
 * Extend this class and implement {@see getSalesforceObjectType()} to define
 * which Salesforce sObject type the loader targets, and optionally override
 * {@see resolveExistingId()} to implement upsert logic.
 *
 * Example (simple create):
 *
 *   class ContactLoader extends SalesforceLoader
 *   {
 *       protected function getSalesforceObjectType(): string
 *       {
 *           return 'Contact';
 *       }
 *
 *       protected function mapRecord(mixed $record): array
 *       {
 *           return [
 *               'FirstName' => $record['first_name'],
 *               'LastName'  => $record['last_name'],
 *               'Email'     => $record['email'],
 *           ];
 *       }
 *   }
 */
abstract class SalesforceLoader extends AbstractLoader
{
    public function __construct(
        protected readonly SalesforceClient $client,
    ) {
    }

    /**
     * Return the Salesforce sObject API name (e.g. "Contact", "Account").
     */
    abstract protected function getSalesforceObjectType(): string;

    /**
     * Map a pipeline record to a Salesforce field array.
     *
     * @return array<string, mixed>
     */
    abstract protected function mapRecord(mixed $record): array;

    /**
     * Return an existing Salesforce record ID to update, or null to create.
     *
     * Override this method to implement upsert (update-or-create) logic.
     */
    protected function resolveExistingId(mixed $record): ?string
    {
        return null;
    }

    protected function doLoad(mixed $record): void
    {
        $data       = $this->mapRecord($record);
        $existingId = $this->resolveExistingId($record);

        if ($existingId !== null) {
            $this->client->update($this->getSalesforceObjectType(), $existingId, $data);
        } else {
            $this->client->create($this->getSalesforceObjectType(), $data);
        }
    }
}
