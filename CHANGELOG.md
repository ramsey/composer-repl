# ramsey/composer-repl Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

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

- do not display package name if it is __root__

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

- discontinue use of deprecated ReflectionParameter::isArray() method

## 1.2.0 - 2020-10-26

### Added

- support environment bootstrapping for the repl

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

- remove hoa/console dependency

## 1.1.0 - 2020-08-28

### Added

- add a standalone `repl` command to the bin directory

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- use internal symfony/process wrapper to avoid conflicts caused by using the symfony/process package bundled with `composer.phar`

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

- use the passed Composer instance more efficiently

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

- remove unnecessary Composer instantiation

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

- rename class/files to avoid "illegal byte sequence" error with unzip

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

- use `bin-dir` for correct path to the Composer executables

## 1.0.0 - 2020-08-25

Initial release.

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
