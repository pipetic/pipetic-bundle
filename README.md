# pipetic-bundle

Salesforce adapter for the [Pipetic](https://github.com/pipetic) organisation.

Designed to be installed in multi-tenant SaaS applications to enable two-way
data synchronisation with Salesforce through an OAuth2-secured connection and
a lightweight ETL pipeline.

---

## Features

- **Salesforce OAuth2** – full authorisation-code flow, token exchange and
  automatic token refresh via PSR-18–compatible HTTP clients.
- **ETL Pipeline** – minimal but extensible Extract → Transform → Load
  orchestrator inspired by
  [wizacode/php-etl](https://github.com/wizacode/php-etl),
  [jwhulette/pipes](https://github.com/jwhulette/pipes), and
  [php-etl/pipeline](https://github.com/php-etl/pipeline).
- **Salesforce Extractor / Loader** – ready-to-extend base classes for pulling
  data from Salesforce (SOQL) and pushing data back (sObject CRUD).
- **Droplet tracking** – uses the Pipetic Droplet model to track the lifecycle
  of each sync item (Pending → Sent / Retrying / Failed).
- **Multi-tenant** – one `OAuthConfig` + `AccessToken` pair per tenant; token
  storage is deliberately left to the consuming application.

---

## Requirements

- PHP >= 8.2
- A PSR-18 HTTP client (e.g. `guzzlehttp/guzzle`, `symfony/http-client`)
- A PSR-17 HTTP factory (e.g. `guzzlehttp/psr7`, `nyholm/psr7`)

---

## Installation

```bash
composer require pipetic/bundle
```

---

## Salesforce OAuth2 Setup

### 1. Configure your Connected App in Salesforce

Create a Connected App in Salesforce Setup and note the **Consumer Key**
(client ID) and **Consumer Secret**.

### 2. Configure the package

Add the following to your `.env` file:

```dotenv
SALESFORCE_CLIENT_ID=your_consumer_key
SALESFORCE_CLIENT_SECRET=your_consumer_secret
SALESFORCE_REDIRECT_URI=https://yourapp.com/salesforce/callback
# Use https://test.salesforce.com for sandboxes
SALESFORCE_LOGIN_URL=https://login.salesforce.com
SALESFORCE_API_VERSION=v59.0
```

### 3. Redirect the user

```php
use Pipetic\Bundle\Salesforce\Auth\OAuthConfig;
use Pipetic\Bundle\Salesforce\Auth\OAuthConnector;

$config    = OAuthConfig::create($clientId, $clientSecret, $redirectUri);
$connector = new OAuthConnector($config, $httpClient, $requestFactory, $streamFactory);

$state = bin2hex(random_bytes(16));
// Store $state in session for CSRF validation

header('Location: ' . $connector->getAuthorizationUrl([], $state));
```

### 4. Handle the callback

```php
// Validate $state from session vs $_GET['state']

$token = $connector->exchangeCodeForToken($_GET['code']);

// Persist $token->toArray() per-tenant (e.g. in a DataNode's metadata)
```

### 5. Make API calls

```php
use Pipetic\Bundle\Salesforce\Api\SalesforceClient;

$client   = new SalesforceClient($token, $httpClient, $requestFactory, $streamFactory);
$contacts = $client->query('SELECT Id, FirstName, LastName FROM Contact LIMIT 10');
```

---

## ETL Pipeline

### Basic usage

```php
use Pipetic\Bundle\Pipeline\Pipeline;

(new Pipeline())
    ->extract(new MyContactExtractor($sfClient))
    ->pipe(new MapFieldsTransformer($fieldMap))
    ->pipe(new ValidateRecordTransformer())
    ->load(new LocalContactLoader($repository))
    ->run();
```

### Custom Salesforce Extractor

```php
use Pipetic\Bundle\Salesforce\Pipeline\SalesforceExtractor;

class ContactExtractor extends SalesforceExtractor
{
    protected function getSoqlQuery(): string
    {
        return 'SELECT Id, FirstName, LastName, Email FROM Contact';
    }
}
```

### Custom Salesforce Loader

```php
use Pipetic\Bundle\Salesforce\Pipeline\SalesforceLoader;

class ContactLoader extends SalesforceLoader
{
    protected function getSalesforceObjectType(): string
    {
        return 'Contact';
    }

    protected function mapRecord(mixed $record): array
    {
        return [
            'FirstName' => $record['first_name'],
            'LastName'  => $record['last_name'],
            'Email'     => $record['email'],
        ];
    }
}
```

---

## Inspiration

- <https://github.com/wizacode/php-etl>
- <https://github.com/jwhulette/pipes>
- <https://github.com/php-etl/pipeline>
