# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/contentful/ContentfulBundle/compare/6.0.1...HEAD)

<!-- PENDING-CHANGES -->
> No meaningful changes since last release.
<!-- /PENDING-CHANGES -->

## [6.0.1](https://github.com/contentful/ContentfulBundle/tree/6.0.1) (2020-03-24)

### Changed

* Replace Travis build badge with CircleCI

## [6.0.0](https://github.com/contentful/ContentfulBundle/tree/6.0.0) (2020-03-24)

### Changed

* Added support for PHP 7.4
* Dropped support for PHP 7.0 & 7.1
* Added support for symfony 5
* Upgraded dependencies

## [5.0.0](https://github.com/contentful/ContentfulBundle/tree/5.0.0) (2020-02-24)

### Changed

* Updated contentful/contenful dependency and releasing as a major version due to breaking changes in dependencies

## [4.0.0](https://github.com/contentful/ContentfulBundle/tree/4.0.0) (2018-12-05)

**ATTENTION**: This release contains breaking changes. Please take extra care when updating to this version.

### Changed

* The bundle now requires the Contentful Delivery SDK version 4. Please check its [upgrade guide](https://github.com/contentful/contentful.php/blob/master/UPGRADE-4.0.md) for more. **[BREAKING]**
* The configuration format has been changed. Refer to the [upgrade guide](UPGRADE-4.0.md) for more. **[BREAKING]**
* The command `contentful:info` was renamed `contentful:delivery:info`.

### Added

* The command `contentful:delivery:debug` was added, and it will print info about the space, locales and content types of the selected client.
* Configured clients now support autowiring by type-hinting either `Contentful\Delivery\Client` or `Contentful\Delivery\Client\ClientInterface` (which is the recommended way). If multiple clients are configured, the autowired client will always be the one configured using `default: true`.

## [3.0.0](https://github.com/contentful/ContentfulBundle/tree/2.0.0) (2018-06-19)

**ATTENTION**: This release contains breaking changes. Please take extra care when updating to this version.

### Changed

* The bundle now requires Symfony 3.4 or 4.x, and the Contentful Delivery SDK version 3. **[BREAKING]**

## [2.0.0](https://github.com/contentful/ContentfulBundle/tree/2.0.0) (2017-06-14)

**ATTENTION**: This release contains breaking changes. Please take extra care when updating to this version.

### Changed
* Use version 2.0 of the Contentful SDK. **[BREAKING]** This release contains breaking changes, you can read more about
them the SDK Changelog for [version 2.0.0](https://github.com/contentful/contentful.php/releases/tag/2.0.0).

## [1.0.0](https://github.com/contentful/ContentfulBundle/tree/1.0.0) (2017-04-26)

### Changed
* Use version 1.0 of the Contentful SDK.

## [0.8.0-beta](https://github.com/contentful/ContentfulBundle/tree/0.8.0-beta) (2017-04-11)

### Added
* Exposed new features from the newer Contentful SDK
  * Overriding URI used to communicate with the Contentful API (`uri_override`)
  * Setting the default locale for the client (`default_locale`)
  * Setting a custom Guzzle instance (`http_client`)
  * Caching the space and content types (`cache`)

### Changed
* Use version 0.8 of the Contentful SDK. **Note:** This release contains breaking changes, you can read more about them
in the change logs for [version 0.7](https://github.com/contentful/contentful.php/releases/tag/0.7.0-beta) and [version 0.8](https://github.com/contentful/contentful.php/releases/tag/0.8.0-beta).

## [0.6.2-beta](https://github.com/contentful/ContentfulBundle/tree/0.6.2-beta) (2017-04-05)

### Changed
* `ProfilerController::details` has been renamed to `ProfilerController::detailsAction`

### Fixed
* `cache:clear` throws an exception in production environments ([#2](https://github.com/contentful/ContentfulBundle/pull/2))

## [0.6.1-beta](https://github.com/contentful/ContentfulBundle/tree/0.6.1-beta) (2016-04-14)

### Changed
* `ProfilerController` is now a service

### Fixed
* Fixed an off-by-one error in the Web Profiler

## [0.6.0-beta](https://github.com/contentful/ContentfulBundle/tree/0.6.0-beta) (2016-03-03)

### Added
* Show metrics about requests in the Web Profiler
* Show headers and response bodies for API calls
* Added ContentfulDataCollector::getErrorCount

### Changed
* Request logging is only enabled by default if `kernel.debug` is enabled. It can be configured for individual clients
by setting `request_logging`.
* Show Contentful info in the Web Debug Toolbar only when a request was performed.

## [0.5.0-beta](https://github.com/contentful/ContentfulBundle/tree/0.5.0-beta) (2016-02-22)

Initial release
