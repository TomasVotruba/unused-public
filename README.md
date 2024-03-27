# Find Unused Public Elements in Your Code

<br>

<div align="center">
    <img src="/docs/unused_public.jpg" style="width: 10em">
</div>

<br>

It's easy to find unused private class elements, because they're not used in the class itself:

```diff
 final class Book
 {
     public function getTitle(): string
     {
         // ...
     }

-    private function getSubtitle(): string
-    {
-        // ...
-    }
}
```

But what about public class elements?

<br>

**How can we detect such element?**

-   find a e.g. public method
-   find all public method calls
-   compare those in simple diff
-   if the public method is not found, it probably unused

That's exactly what this package does.

<br>

This technique is very useful for private projects and to detect accidentally open public API that should be used only locally.

<br>

## Install

```bash
composer require tomasvotruba/unused-public --dev
```

The package is available on PHP 7.2-8.1 versions in tagged releases.

<br>

## Usage

With [PHPStan extension installer](https://github.com/phpstan/extension-installer), everything is ready to run.

Enable each item on their own with simple configuration:

```yaml
# phpstan.neon
parameters:
    unused_public:
        methods: true
        properties: true
        constants: true
```

<br>

Do you want to check local-only method calls that should not be removed, but be turned into `private`/`protected` instead?

```yaml
# phpstan.neon
parameters:
    unused_public:
        local_methods: true
```

<br>

## Exclude methods called in templates

Some methods are used only in TWIG or Blade templates, and could be reported false positively as unused.

```twig
{{ book.getTitle() }}
```

How can we exclude them? Add your TWIG or Blade template directories in config to exclude methods names:

```neon
# phpstan.neon
parameters:
    unused_public:
        template_paths:
            - templates
```

<br>

## Known Limitations

In some cases, the rules report false positives:

-   when used only in templates, apart Twig paths, it's not possible to detect them

<br>

## Skip Public-Only Methods

Open-source vendors design public API to be used by projects. Is element reported as unused, but it's actually designed to be used public?

Mark the class or element with `@api` annotation to skip it:

```php
final class Book
{
    /**
     * @api
     */
    public function getName()
    {
        return $this->name;
    }
}
```

You can also use the `@required` or `@internal` to make it clearer in certain situations that you want the check skipped or that it is used internally. In this situation in Laravel, while the direct call is not present, it can be used as a property when [referencing relationships in Laravel Eloquent models](https://laravel.com/docs/11.x/eloquent-relationships#defining-relationships):

```php
<?php

class User extends Model
{
    // ...

    /**
     * @internal
     */
    public $timestamps = false;

    /**
     * @required
     */
    public function post(): HasOne
    {
        return $this->hasOne(Post::class);
    }

    // ...
}
```
