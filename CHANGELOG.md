# ramsey/composer-repl Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [Unreleased]

### Added

### Changed

### Deprecated

### Removed

### Fixed

### Security


## [1.0.3] - 2020-08-26

### Fixed

* remove unnecessary Composer instantiation

  This appears to fix `proc_open()` errors seen after requiring the
  package and running any `composer` command.


## [1.0.2] - 2020-08-25

### Fixed

* rename class/files to avoid "illegal byte sequence" error with unzip


## [1.0.1] - 2020-08-25

### Fixed

* use `bin-dir` for correct path to the Composer executables


## [1.0.0] - 2020-08-25

### Added

* create a REPL plugin for Composer


[Unreleased]: https://github.com/ramsey/composer-repl/compare/1.0.3...HEAD
[1.0.3]: https://github.com/ramsey/composer-repl/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/ramsey/composer-repl/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/ramsey/composer-repl/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/ramsey/composer-repl/commits/1.0.0
