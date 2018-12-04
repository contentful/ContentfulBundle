# ContentfulBundle

[![Packagist](https://img.shields.io/packagist/v/contentful/contentful-bundle.svg?style=for-the-badge)](https://packagist.org/packages/contentful/contentful-bundle)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/contentful/contentful-bundle.svg?style=for-the-badge)](https://packagist.org/packages/contentful/contentful-bundle)
[![Travis](https://img.shields.io/travis/contentful/ContentfulBundle.svg?style=for-the-badge)](https://travis-ci.org/contentful/ContentfulBundle)
[![Packagist](https://img.shields.io/github/license/contentful/ContentfulBundle.svg?style=for-the-badge)](https://packagist.org/packages/contentful/contentful-bundle)

> Symfony Bundle for the Contentful Delivery SDK. This bundle requires PHP 7.0 or higher, and Symfony 3.4 or higher. It also requires Twig to be installed.

# Setup

Add this package to your application by using [Composer](https://getcomposer.org/) and executing the following command:

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
        main:
            space: cfexampleapi
            token: b4c0n73n7fu1
```

You can also configure multiple clients and enable the preview mode:

``` yaml
contentful:
    delivery:
        main:
            default: true
            space: cfexampleapi
            token: b4c0n73n7fu1
        preview:
            space: cfexampleapi
            token: b4c0n73n7fu1
            api: preview
```

## Documentation

[Getting Started Tutorial](https://www.contentful.com/developers/docs/php/tutorials/getting-started-with-contentful-and-symfony/)

## What is Contentful?

[Contentful](https://www.contentful.com) provides a content infrastructure for digital teams to power content in websites, apps, and devices. Unlike a CMS, Contentful was built to integrate with the modern software stack. It offers a central hub for structured content, powerful management and delivery APIs, and a customizable web app that enable developers and content creators to ship digital products faster.

## License

Copyright (c) 2015-2017 Contentful GmbH. Code released under the MIT license. See [LICENSE](LICENSE) for further details.
