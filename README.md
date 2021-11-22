# Encrypting and signing data using private/public keys For All version of php7 only

[![Latest Version on Packagist](https://img.shields.io/packagist/v/Justmd5/crypto.svg?style=flat-square)](https://packagist.org/packages/Justmd5/crypto)
![Tests](https://github.com/Justmd5/crypto/workflows/Tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/Justmd5/crypto.svg?style=flat-square)](https://packagist.org/packages/Justmd5/crypto)


This package allows you to easily generate a private/public key pairs, and encrypt/decrypt messages using those keys.

```php
use Justmd5\Crypto\Rsa\KeyPair;
use Justmd5\Crypto\Rsa\PrivateKey;
use Justmd5\Crypto\Rsa\PublicKey;

// generating an RSA key pair
list($privateKey,$publicKey)   = (new KeyPair())->generate();

// when passing paths, the generated keys will be written those paths
(new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey);

$data = 'my secret data';

$privateKey = PrivateKey::fromFile($pathToPrivateKey);
$encryptedData = $privateKey->encrypt($data); // returns something unreadable

$publicKey = PublicKey::fromFile($pathToPublicKey);
$decryptedData = $publicKey->decrypt($encryptedData); // returns 'my secret data'
```

Most functions in this package are wrappers around `openssl_*` functions to improve DX.

## Installation

You can install the package via composer:

```bash
composer require justmd5/crypto
```

## Usage

You can generate a key pair using the `generate` function on the `KeyPair` class.

```php
use Justmd5\Crypto\Rsa\KeyPair;

 list($privateKey,$publicKey)   = (new KeyPair())->generate();
```

You can write the keys to disk, by passing paths to the `generate` function. 

```php
// when passing paths, the generate keys will to those paths
(new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey);
```

You can protect the private key with a password by using the `password` method:

```php
 $generate = (new KeyPair())->password('my-password')->generate();
list(passwordProtectedPrivateKey,$publicKey)   = (new KeyPair())->generate();
```

When using a password to generating a private key, you will need that password when instantiating the `PrivateKey` class.

### Loading keys

To load a key from a file use the `fromFile` static method.

```php
Justmd5\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey);
Justmd5\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey);
```

Alternatively, you can also create a key object using a string.

```php
Justmd5\Crypto\Rsa\PrivateKey::fromString($privateKeyString);
Justmd5\Crypto\Rsa\PublicKey::fromString($publicKeyString);
```

If the private key is password protected, you need to pass the password as the second argument.

```php
Justmd5\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey, $password);
Justmd5\Crypto\Rsa\PrivateKey::fromString($privateKeyString, $password);
```

If you do not specify the right password, a `Justmd5\Crypto\Exceptions\InvalidPrivateKey` exception will be thrown.

### Encrypting a message with a private key, decrypting with the public key

Here's how you can encrypt data using the private key, and how to decrypt it using the public key.

```php
$data = 'my secret data';

$privateKey = Justmd5\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey);
$encryptedData = $privateKey->encrypt($data); // encrypted data contains something unreadable

$publicKey = Justmd5\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey);
$decryptedData = $publicKey->decrypt($encryptedData); // decrypted data contains 'my secret data'
```

If `decrypt` cannot decrypt the given data (maybe a non-matching private key was used to encrypt the data, or maybe tampered with the data), an exception of class `Justmd5\Crypto\Exceptions\CouldNotDecryptData` will be thrown.

### Encrypting a message with a public key, decrypting with the private key

Here's how you can encrypt data using the public key, and how to decrypt it using the private key.

```php
$data = 'my secret data';

$publicKey = Justmd5\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey);
$encryptedData = $publicKey->encrypt($data); // encrypted data contains something unreadable

$privateKey = Justmd5\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey);
$decryptedData = $privateKey->decrypt($encryptedData); // decrypted data contains 'my secret data'
```

If `decrypt` cannot decrypt the given data (maybe a non-matching public key was used to encrypt the data, or maybe tampered with the data), an exception of class `Justmd5\Crypto\Exceptions\CouldNotDecryptData` will be thrown.

### Determining if the data can be decrypted

Both the `PublicKey` and `PrivateKey` class have a `canDecrypt` method to determine if given data can be decrypted.

```php
Justmd5\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey)->canDecrypt($data); // returns a boolean;
Justmd5\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey)->canDecrypt($data); // returns a boolean;
```

### Signing and verifying data

The `PrivateKey` class has a method `sign` to generate a signature for the given data. The `verify` method on the `PublicKey` class can be used to verify if a signature is valid for the given data.

If `verify` returns `true`, you know for certain that the holder of the private key signed the message, and that it was not tampered with.

```php
$signature = Justmd5\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey)->sign('my message'); // returns a string

$publicKey = Justmd5\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey);

$publicKey->verify('my message', $signature) // returns true;
$publicKey->verify('my modified message', $signature) // returns false;
```

## Alternatives

This package aims to be very lightweight and easy to use. If you need more features, consider using of one these alternatives:

- [paragonie/halite](https://github.com/paragonie/halite)
- [vlucas/pikirasa](https://github.com/vlucas/pikirasa)
- [laminas/crypt](https://docs.laminas.dev/laminas-crypt/)
- [phpseclib/phpseclib](https://github.com/phpseclib/phpseclib)

## A word on the usage of RSA

At the time of writing, RSA is secure enough for the use case we've built this package for.

To know more about why RSA might not be good enough for you, read [this post on public-key encryption at Paragonie.com](https://paragonie.com/blog/2016/12/everything-you-know-about-public-key-encryption-in-php-is-wrong#php-openssl-rsa-bad-default)

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Thanks To
[spatie/crypto](https://github.com/spatie/crypto)

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
