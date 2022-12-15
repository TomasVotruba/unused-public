# Find Unused Public Constants, Properties and Methods in Your Code

It's easy to find unused private class elements, because they're not used in the class itself. But what about public class elements?


To detect an unused public class element, you have to:

* find a public method
* find all public method calls
* compare those and if not found, it's unused

That's exactly what this package does.

<br>

This technique is very useful for private projects and to detect accidentally open public API that should be used only locally.

## Install

```bash
composer require tomasvotruba/unused-public --dev
```

## Usage

With PHPStan extension installer, everything is ready to run.

Enable each item on their own with simple configuration:

```neon
parameters:
    unused_public:
        methods: true
        properties: true
        constants: true
        static_properties: true
```
