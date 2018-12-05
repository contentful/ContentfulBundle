# UPGRADE FROM 3.x to 4.0

## Changes to configuration format

The way of configuring clients used to support multiple ways of doing the same thing, whereas now the same structure is applied all the time, whether the user is using one or multiple clients. These are examples of the most basic configurations, before and after:

``` yaml
# Up to version 3.0
contentful:
  delivery:
    token: ACCESS_TOKEN
    space: SPACE_ID

# From version 4.0
contentful:
  delivery:
    main:
      token: ACCESS_TOKEN
      space: SPACE_ID
```

This is the complete available configuration structure:

```yaml
contentful:
  delivery:
    main:
      # This value marks the one client that will be available for autowiring
      # If only one client is defined, this value is not required.
      default: true
      # Required
      token: ACCESS_TOKEN
      # Required
      space: SPACE_ID
      # Optional, defaults to "master".
      environment: master
      # Optional, either "delivery" or "preview", defaults to "delivery".
      api: delivery
      options:
        # Optional, which locale to use for all API calls, defaults to null.
        locale: en-US
        # Optional, which host to use a base for all API calls,
        # it can be useful when working with proxies, defaults to null.
        host: https://cdn.contentful.com
        # Optional, which PSR-3 logger implementation to use.
        # You can set it to the name of the service, or to "null" to disable logging.
        # Defaults to the default class implementing Psr\Log\LoggerInterface
        logger: app.logger
        # Optional,
        client: app.client
        # Optional, a set of values that determine how caching is handled by the SDK.
        cache:
          # Optional, which PSR-6 cache implementation to use.
          # You can set it to the name of the service, or to "null" to disable caching.
          # Defaults to the default class implementing Psr\Cache\CacheItemPoolInterface
          pool: app.cache
          # Optional, if set to true the cache pool will be populated
          # during runtime use instead of having to preload data through cache warming.
          # Defaults to false.
          runtime: true
          # Optional, if  set to true the cache pool will be populated
          # with content (entries and assets) and not just structure (space, environment and content types).
          # Defaults to false.
          content: true
```

The name `main` is completely arbitrary, and anything can be used. The name will be used to create the appropriate service. For instance, calling a client `main` will result in the service `contentful.delivery.main_client` to be created. Any number of clients can be created this way, for instance you might want to have a `main` client for general use, and a `preview` client which connects to the Preview API instead of the Delivery API (though in this scenario you might be better off by creating different configurations for Symfony environments, depending on your use case).

