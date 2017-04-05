# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/contentful/ContentfulBundle/compare/0.6.1-beta...HEAD)

### Fixed
* `cache:clear` throws an exception in production environments ([#2](https://github.com/contentful/ContentfulBundle/pull/2))

## [0.6.1-beta](https://github.com/contentful/ContentfulBundle/tree/0.6.1-beta) (2016-04-14)

### Changed
* `ProfilerController` is now a service
* `ProfilerController::details` has been renamed to `ProfilerController::detailsAction`

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
