# pipetic-bundle

Base package for the [Pipetic](https://github.com/pipetic) organisation.

Provides the core ETL pipeline primitives and Droplet tracking model used by
Pipetic adapter packages (e.g.
[pipetic/salesforce](https://github.com/pipetic/salesforce)).

---

## Features

- **ETL Pipeline** – minimal but extensible Extract → Transform → Load
  orchestrator inspired by
  [wizacode/php-etl](https://github.com/wizacode/php-etl),
  [jwhulette/pipes](https://github.com/jwhulette/pipes), and
  [php-etl/pipeline](https://github.com/php-etl/pipeline).
- **Droplet tracking** – lightweight model for tracking the lifecycle of each
  sync item (Pending → Sent / Retrying / Failed).

---

## Requirements

- PHP >= 8.2

---

## Installation

```bash
composer require pipetic/bundle
```

---

## ETL Pipeline

### Basic usage

```php
use Pipetic\Bundle\Pipeline\Pipeline;

(new Pipeline())
    ->extract(new MySourceExtractor())
    ->pipe(new MapFieldsTransformer($fieldMap))
    ->pipe(new ValidateRecordTransformer())
    ->load(new MyDestinationLoader())
    ->run();
```

### Custom Extractor

```php
use Pipetic\Bundle\Pipeline\AbstractExtractor;

class MySourceExtractor extends AbstractExtractor
{
    protected function doExtract(): iterable
    {
        foreach ($this->source->getRecords() as $record) {
            yield $record;
        }
    }
}
```

### Custom Transformer

Return `null` from `doTransform()` to skip (filter out) a record.

```php
use Pipetic\Bundle\Pipeline\AbstractTransformer;

class MapFieldsTransformer extends AbstractTransformer
{
    protected function doTransform(mixed $record): mixed
    {
        return [
            'name'  => $record['full_name'],
            'email' => $record['email_address'],
        ];
    }
}
```

### Custom Loader

```php
use Pipetic\Bundle\Pipeline\AbstractLoader;

class MyDestinationLoader extends AbstractLoader
{
    protected function doLoad(mixed $record): void
    {
        $this->repository->save($record);
    }
}
```

### Processed / skipped counts

```php
$pipeline->run();

echo $pipeline->getProcessedCount(); // records successfully loaded
echo $pipeline->getSkippedCount();   // records filtered out by a transformer
```

---

## Inspiration

- <https://github.com/wizacode/php-etl>
- <https://github.com/jwhulette/pipes>
- <https://github.com/php-etl/pipeline>
