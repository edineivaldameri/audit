# AUDIT

Every time we talk about auditing information in software, we ask ourselves what is the best way to do it and how to make sure nothing is missed.

With this in mind, this audit package was created, which uses the database trigger's structure to perform the audit. This allows us to identify all changes that have occurred, whether through a system or directly through the database manager.

After installing the package, records will be saved in the 'audits' table.

The data "before" and "after" the actions performed by the user will always be stored.

A "context" is stored, containing information about the origin and user responsible for the action that occurred.

You can see an example of an audit record below:

```php
array:8 [
  "id" => 2
  "schema" => "public"
  "table" => "users"
  "event" => "INSERT"
  "context" => array:4 [
    "ip" => "127.0.0.1"
    "origin" => "http://localhost"
    "user_id" => 1
    "user_name" => "Tracy Nienow MD"
  ]
  "before" => null
  "after" => array:5 [
    "id" => 2
    "name" => "Hildegard Strosin Jr."
    "email" => "qschoen@example.org"
    "created_at" => "2024-09-16T20:59:20"
    "updated_at" => "2024-09-16T20:59:20"
  ]
  "date" => "2024-09-16 17:59:21"
]
```

## Installation

You can install the package via composer:

```bash
composer require edineivaldameri/audit
```

After executing the command:
```bash
php artisan audit:install
```

Once the installation is complete, the package will automatically identify the creation of new tables and add auditing without the need for a new installation.

## Configuration
Once installed, you can configure the package configuration file using the command:

```bash
php artisan vendor:publish --provider="EdineiValdameri\Laravel\Audit\Providers\AuditServiceProvider" --tag='config'
```

With this, a file called audit.php will be available in the settings directory, in which you can enable/disable auditing, as well as insert tables that you do not want to audit.

If you want to temporarily disable audits, just insert in your .env: `AUDIT_ENABLED=false`.

### Ignoring tables
When installing and publishing the configuration file, you can insert tables that you want to ignore when performing the audit, just insert their names in the `skip` property.

### Multiple schemas
You can configure multiple database schemas by adding each of them to the `schemas` property in the settings.

But remember you can only ignore these tables before running the `php artisan audit:install` command.

## Uninstall
To uninstall to run the command:

```bash
php artisan audit:uninstall
```

## Support
This package has been tested and works well with `PostgreSql` databases, new databases are being tested and may have their support added over time.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Edinei Valdameri](https://github.com/edineivaldameri)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
