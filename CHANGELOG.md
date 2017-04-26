# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

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
