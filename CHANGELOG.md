# ramsey/composer-repl Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 1.4.0 - 2022-01-18

### Added

- Nothing.

### Changed

- Move all library code to [ramsey/composer-repl-lib](https://github.com/ramsey/composer-repl-lib). No class names or namespaces have changed, and since this package requires ramsey/composer-repl-lib, there are no BC breaks.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.3.0 - 2022-01-02

### Added

- Allow Symfony packages in the version 6.x series

### Changed

- Increase lower-boundary constraints for symfony/console and symfony/process to 4.4.30 and 5.3.7

- Update PsySH to version 0.11.x

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.2.3 - 2021-08-07

Maintenance release

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Bump composer/composer dependency to `^1.10.22 || ^2.0.13`

## 1.2.2 - 2021-03-04

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Do not display package name if it is `__root__`

## 1.2.1 - 2021-02-20

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Discontinue use of deprecated `ReflectionParameter::isArray()` method

## 1.2.0 - 2020-10-26

### Added

- Support environment bootstrapping for the REPL

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.1.1 - 2020-09-02

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Remove hoa/console dependency

## 1.1.0 - 2020-08-28

### Added

- Add a standalone `repl` command to the bin directory

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Use internal symfony/process wrapper to avoid conflicts caused by using the symfony/process package bundled with `composer.phar`

## 1.0.4 - 2020-08-26

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Use the passed Composer instance more efficiently

## 1.0.3 - 2020-08-26

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Remove unnecessary Composer instantiation

  This appears to fix `proc_open()` errors seen after requiring the
  package and running any `composer` command.

## 1.0.2 - 2020-08-25

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Rename class/files to avoid "illegal byte sequence" error with unzip

## 1.0.1 - 2020-08-25

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Use `bin-dir` for correct path to the Composer executables

## 1.0.0 - 2020-08-25

Initial release

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
