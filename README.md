# MajoraOAuthServerBundle

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LinkValue/MajoraOAuthServerBundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/LinkValue/MajoraOAuthServerBundle/?branch=develop) [![Build Status](https://travis-ci.org/LinkValue/MajoraOAuthServerBundle.svg?branch=develop)](https://travis-ci.org/LinkValue/MajoraOAuthServerBundle) [![Code Coverage](https://scrutinizer-ci.com/g/LinkValue/MajoraOAuthServerBundle/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/LinkValue/MajoraOAuthServerBundle/?branch=develop) [![Total Downloads](https://poser.pugx.org/majora/oauth-server-bundle/downloads)](https://packagist.org/packages/majora/oauth-server-bundle) [![Latest Stable Version](https://poser.pugx.org/majora/oauth-server-bundle/v/stable)](https://packagist.org/packages/majora/oauth-server-bundle) [![License](https://poser.pugx.org/majora/oauth-server-bundle/license)](https://packagist.org/packages/majora/oauth-server-bundle)

Provides a lightweight, extensive and highly customizable OAuth Server as a Symfony Bundle.

Features include :

* Domain Driven Design approch
* Entity models and basic implementation for authenticated accounts, applications and access tokens
* Full abstraction of loading / persisting entities of any kind
* Generic extension system of custom grant types integration
* Server service which grants access tokens with "password" credentials

Features to come in v2.x :

* In memory, Filer, Redis & Doctrine drivers
* Anonymous grant type extension
* Refresh token generation and grant type

**Note** : version 2.x is totally work in progress, many classes, interfaces and services can be modified without deprecation step.

**Caution** : this bundle purpose is to be used in REST API centric projets, not for standard web. For this purpose, we recommend [FOSOAuthServerBundle](https://github.com/FriendsOfSymfony/FOSOAuthServerBundle), a full featured OAuth server.

## Documentation

* [Read the Documentation for v1.0](https://github.com/LinkValue/MajoraOAuthServerBundle/blob/v1.0/README.md)
* [Read the Documentation for v2.x](https://github.com/LinkValue/MajoraOAuthServerBundle/blob/master/src/Majora/Bundle/OAuthServerBundle/Resources/doc/index.md)

## Installation

All the installation instructions are located in the documentation.

## License

This bundle is under the MIT license. See the complete license :

    LICENSE

## Credits

- [Quentin Cerny](https://github.com/Nyxis), [Link Value](http://link-value.fr/), and [all contributors](https://github.com/LinkValue/MajoraOAuthServerBundle/contributors)
- Inspired by [FOSOAuthServerBundle](https://github.com/FriendsOfSymfony/FOSOAuthServerBundle)
