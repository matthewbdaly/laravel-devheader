# laravel-devheader

Laravel middleware that adds a header to the page when not in production so you don't mix up dev, staging and production environments.

How do I use it?
----------------

Install it like this:

```bash
$ composer require matthewbdaly/laravel-devheader
```

Then include the middleware in the `web` group in `app/Http/Kernel.php`:

```php
Matthewbdaly\LaravelDevheader\Http\Middleware\DevHeader::class
```
