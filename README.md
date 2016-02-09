# MajoraOAuthServerBundle

[![Latest Stable Version](https://poser.pugx.org/majora/oauth-server-bundle/v/stable)](https://packagist.org/packages/majora/oauth-server-bundle) [![Total Downloads](https://poser.pugx.org/majora/oauth-server-bundle/downloads)](https://packagist.org/packages/majora/oauth-server-bundle) [![Latest Unstable Version](https://poser.pugx.org/majora/oauth-server-bundle/v/unstable)](https://packagist.org/packages/majora/oauth-server-bundle) [![License](https://poser.pugx.org/majora/oauth-server-bundle/license)](https://packagist.org/packages/majora/oauth-server-bundle)

Provides a lightweight, extensive and highly customizable OAuth Server as a Symfony Bundle.

Features include :

    - Domain Driven Design approch
    - Entity models and basic implementation for authenticated accounts, applications and access tokens
    - Full abstraction of loading / persisting entities of any kind
    - Generic extension system of custom grant types integration
    - Server service which grants access tokens with "password" credentials

Features to come in v2.x :

    - In memory, Filer, Redis & Doctrine drivers
    - Anonymous grant type extension
    - Refresh token generation and grant type

** Caution ** : this bundle purpose is to be used in REST API centric projets, not for standard web. For this purpose, we recommend [FOSOAuthServerBundle](https://github.com/FriendsOfSymfony/FOSOAuthServerBundle), a full featured OAuth server.

## Documentation

The source of the documentation is stored in the `src/Majora/Bundle/OAuthServerBundle/Resources/doc/` folder: [Read the Documentation for master](https://github.com/LinkValue/MajoraOAuthServerBundle/blob/master/src/Majora/Bundle/OAuthServerBundle/Resources/doc/index.md)

## Installation

All the installation instructions are located in the documentation.

## License

This bundle is under the MIT license. See the complete license :

    LICENSE

## Credits

- [Quentin Cerny](https://github.com/Nyxis), [Link Value](http://link-value.fr/), and [all contributors](https://github.com/LinkValue/MajoraOAuthServerBundle/contributors)
- Inspired by [FOSOAuthServerBundle](https://github.com/FriendsOfSymfony/FOSOAuthServerBundle)
