# Find Unused Public Constants, Properties and Methods in Your Code

It's easy to find unused private class elements, because they're not used in the class itself. But what about public class elements?

<br>

**How to detect an unused public class element?**

* find a e.g. public method
* find all public method calls
* compare those in simple diff
* if the public method is not found, it probably unused

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

With PHPStan extension installer, everything is ready to run.

Enable each item on their own with simple configuration:

```neon
# phpstan.neon
parameters:
    unused_public:
        methods: true
        properties: true
        constants: true
        static_properties: true
```

<br>

## Known Limitations

In some cases, the method reports false positives:

* it's not possible to detect unused public method that are called only in Twig templates
* following cases are skipped
    * public function in Twig extensions - those are functions/filters callable

<br>

Is element reported as unused, but it's actually used?

Mark the class or element wit `@api` to declare it as public API and skip it:

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
