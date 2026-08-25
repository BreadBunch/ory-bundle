# ory-bundle

OryBundle is a native bundle for integrating Ory identity services (`Kratos`, `Oathkeeper`, `Ory Network`) with the Symfony 8 Security Component.

The bundle handles the low-level tasks of intercepting HTTP requests, validating session cookies (or Bearer tokens) via the official `ory/client` SDK, and transparently authenticating the user within the Symfony ecosystem.

## Installation

```bash
composer require breadbunch/ory-bundle
```

## License

[GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007](./LICENSE)
