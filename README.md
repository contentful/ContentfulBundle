ContentfulBundle
================

[![Build Status](https://travis-ci.org/contentful/ContentfulBundle.svg?branch=master)](https://travis-ci.org/contentful/ContentfulBundle)
[![codecov](https://codecov.io/gh/contentful/ContentfulBundle/branch/master/graph/badge.svg)](https://codecov.io/gh/contentful/ContentfulBundle)

Symfony Bundle for the Contentful SDK.

[Contentful][1] is a content management platform for web applications, mobile apps and connected devices. It allows you to create, edit & manage content in the cloud and publish it anywhere via powerful API. Contentful offers tools for managing editorial teams and enabling cooperation between organizations.

This Bundle requires at least PHP 5.5.9 and Symfony 2.7. PHP 7 and Symfony 3 are supported.

# Setup

## Prerequisites

To make this bundle works, make sure the `@templating` service [is correctly declared](https://symfony.com/doc/3.2/templating/templating_service.html) by setting the following to the `framework` config:

```yml
framework:
    templating: { engines: ['twig'] }
```

To add this package to your `composer.json` and install it execute the following command:

```bash
php composer.phar require contentful/contentful-bundle
````

## Add ContentfulBundle to your application kernel

```php
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

The simplest configuration includes just the space ID and token:

```yaml
contentful:
  delivery:
    space: cfexampleapi
    token: b4c0n73n7fu1
```

You can also configure multiple clients and enable the preview mode:

```yaml
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

Documentation
=============

[Getting Started Tutorial](https://www.contentful.com/developers/docs/php/tutorials/getting-started-with-contentful-and-symfony/)

License
=======

Copyright (c) 2015-2017 Contentful GmbH. Code released under the MIT license. See [LICENSE][2] for further details.

 [1]: https://www.contentful.com
 [2]: LICENSE
