<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Salesforce\Pipeline;

use Pipetic\Bundle\Pipeline\AbstractExtractor;
use Pipetic\Bundle\Salesforce\Api\SalesforceClient;

/**
 * Base extractor for pulling records from Salesforce via SOQL.
 *
 * Extend this class and implement {@see getSoqlQuery()} to define which
 * Salesforce object/fields to extract.
 *
 * Example:
 *
 *   class ContactExtractor extends SalesforceExtractor
 *   {
 *       protected function getSoqlQuery(): string
 *       {
 *           return 'SELECT Id, FirstName, LastName, Email FROM Contact';
 *       }
 *   }
 */
abstract class SalesforceExtractor extends AbstractExtractor
{
    public function __construct(
        protected readonly SalesforceClient $client,
    ) {
    }

    /**
     * Return the SOQL query whose results will be extracted.
     */
    abstract protected function getSoqlQuery(): string;

    /**
     * Optionally transform a raw Salesforce record array before it enters
     * the transformer chain.  Override to add extraction-time normalisation.
     *
     * @param array<string, mixed> $record
     *
     * @return array<string, mixed>
     */
    protected function transformRecord(array $record): array
    {
        return $record;
    }

    protected function doExtract(): iterable
    {
        foreach ($this->client->query($this->getSoqlQuery()) as $record) {
            yield $this->transformRecord($record);
        }
    }
}
