framework-oauth2 [![Build Status](https://travis-ci.org/idealistsoft/framework-oauth2.png?branch=master)](https://travis-ci.org/idealistsoft/framework-oauth2)
=============

[![Coverage Status](https://coveralls.io/repos/idealistsoft/framework-oauth2/badge.png)](https://coveralls.io/r/idealistsoft/framework-oauth2)
[![Latest Stable Version](https://poser.pugx.org/idealistsoft/framework-oauth2/v/stable.png)](https://packagist.org/packages/idealistsoft/framework-oauth2)
[![Total Downloads](https://poser.pugx.org/idealistsoft/framework-oauth2/downloads.png)](https://packagist.org/packages/idealistsoft/framework-oauth2)

oauth2 module for Idealist Framework

## Installation

1. Add the composer package in the require section of your app's `composer.json` and run `composer update`

2. Generate the private key with:
```
openssl genrsa -out jwt_privkey.pem 2048
```

3. Generate the public key with:
```
openssl rsa -in jwt_privkey.pem -pubout -out jwt_pubkey.pem
```

The public and private key should each be stored in the base directory of your app.