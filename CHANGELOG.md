# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/contentful/ContentfulBundle/compare/0.5.0-beta...HEAD)

### Added
* Show metrics about requests in the Web Profiler
* Show headers and response bodies for API calls

### Changed
* Request logging is only enabled by default if `kernel.debug` is enabled. It can be configured for individual clients
by setting `request_logging`.
* Show Contentful info in the Web Debug Toolbar only when a request was performed.

## [0.5.0-beta](https://github.com/contentful/ContentfulBundle/tree/0.5.0-beta) (2016-02-22)

Initial release
