<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Utility;

use ByTIC\PackageBase\Utility\ModelFinder;
use Pipetic\Bundle\PipeticServiceProvider;
use Marktic\Pipetic\PipeticStatuses\Models\PipeticStatuses;
use Marktic\Pipetic\Contacts\Models\Contacts;
use Marktic\Pipetic\ExternalSystems\Communications\Models\Communications;
use Marktic\Pipetic\InvoiceLines\Models\InvoiceLines;
use Marktic\Pipetic\Invoices\Models\Invoices;
use Marktic\Pipetic\LegalEntities\Models\LegalEntities;
use Marktic\Pipetic\Parties\Models\Parties;
use Marktic\Pipetic\PostalAddresses\Models\PostalAddresses;
use Nip\Records\RecordManager;

/**
 * Class PipeticModels.
 */
class PipeticModels extends ModelFinder
{
    public const DROPS = 'drops';

    public static function drops(): Invoices|RecordManager
    {
        return static::getModels(self::DROPS, Invoices::class);
    }

    public static function dropsClass(): string
    {
        return static::getConfigVar('models.' . self::DROPS, Invoices::class);
    }

    protected static function packageName(): string
    {
        return PipeticServiceProvider::NAME;
    }
}
