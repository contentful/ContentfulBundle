# ContentfulBundle

[![Packagist](https://img.shields.io/packagist/v/contentful/contentful-bundle.svg?style=for-the-badge)](https://packagist.org/packages/contentful/contentful-bundle)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/contentful/contentful-bundle.svg?style=for-the-badge)](https://packagist.org/packages/contentful/contentful-bundle)
[![Travis](https://img.shields.io/travis/contentful/ContentfulBundle.svg?style=for-the-badge)](https://travis-ci.org/contentful/ContentfulBundle)
[![Packagist](https://img.shields.io/github/license/contentful/ContentfulBundle.svg?style=for-the-badge)](https://packagist.org/packages/contentful/contentful-bundle)
[![Codecov](https://img.shields.io/codecov/c/github/contentful/ContentfulBundle.svg?style=for-the-badge)](https://codecov.io/gh/contentful/ContentfulBundle)

Symfony Bundle for the Contentful SDK.

## What is Contentful?

[Contentful](https://www.contentful.com) provides a content infrastructure for digital teams to power content in websites, apps, and devices. Unlike a CMS, Contentful was built to integrate with the modern software stack. It offers a central hub for structured content, powerful management and delivery APIs, and a customizable web app that enable developers and content creators to ship digital products faster.

This bundle requires at least PHP 5.5.9 and Symfony 2.7. PHP 7 and Symfony 3 are supported.

# Setup

To add this package to your `composer.json` and install it execute the following command:

``` bash
composer require contentful/contentful-bundle
```

## Add ContentfulBundle to your application

### Symfony 4

``` php
// config/bundles.php
return [
    // ...
    Contentful\ContentfulBundle\ContentfulBundle::class => ['dev' => true],
    // ...
];
```

### Symfony 3

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Contentful\ContentfulBundle\ContentfulBundle(),
        // ...
    );
}
```

## Configuration example

The simplest configuration includes just the space ID and token. Add these settings to either `app/config.yml` (Symfony 3) or create `config/packages/contentful.yaml` (Symfony 4):

``` yaml
contentful:
    delivery:
        space: cfexampleapi
        token: b4c0n73n7fu1
```

You can also configure multiple clients and enable the preview mode:

``` yaml
contentful:
    delivery:
        default_client: exampleapi
        clients:
            exampleapi:
                space: cfexampleapi
                token: b4c0n73n7fu1
            exapleapi_preview:
                space: cfexampleapi
                token: b4c0n73n7fu1
                preview: true
```

## Requisites

This bundle uses Twig. Make sure it is enabled in your configuration.

## Documentation

[Getting Started Tutorial](https://www.contentful.com/developers/docs/php/tutorials/getting-started-with-contentful-and-symfony/)

## License

Copyright (c) 2015-2017 Contentful GmbH. Code released under the MIT license. See [LICENSE](LICENSE) for further details.
