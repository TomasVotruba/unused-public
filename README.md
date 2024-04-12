# Find Unused Public Elements in Your Code

<br>

<div align="center">
    <img src="/docs/unused_public.jpg" style="width: 10em">
</div>

<br>

It's easy to find unused private class elements, because they're not used in the class itself. But what about public methods/properties/constants?

```diff
 final class Book
 {
     public function getTitle(): string
     {
         // ...
     }

-    public function getSubtitle(): string
-    {
-        // ...
-    }
}
```

**How can we detect unused public element?**

* find a public method
* find all public method calls in code and templates
* if the public method is not found, it probably unused

That's exactly what this package does.

<br>

This technique is very useful for private projects and to detect accidentally used `public` modifier that should be changed to `private` as called locally only.

<br>

## Install

```bash
composer require tomasvotruba/unused-public --dev
```

The package is available for PHP 7.2+ version.

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

Do you have hundreds of reported public method? You don't have time to check them all, but want to handle them gradually?

Set maximum allowed % configuration instead:

```yaml
# phpstan.neon
parameters:
    unused_public:
        methods: 2.5
```

This means maximum 2.5 % of all public methods is allowed as unused:

* If it's 5 %, you'll be alerted.
* If it's 1 %, it will be skipped as tolerated.

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

* when used only in templates, apart Twig paths, it's not possible to detect them

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
