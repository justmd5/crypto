<?php

namespace Justmd5\Crypto\Tests\Rsa;

use Justmd5\Crypto\Rsa\Exceptions\InvalidPrivateKey;
use Justmd5\Crypto\Rsa\KeyPair;
use Justmd5\Crypto\Rsa\PrivateKey;
use Justmd5\Crypto\Tests\TestCase;

class KeyPairTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_private_and_public_key()
    {
        $generate = (new KeyPair())->generate();
        $privateKey = $generate[0];
        $publicKey = $generate[1];

        $this->assertStringStartsWith('-----BEGIN PRIVATE KEY-----', $privateKey);
        $this->assertStringStartsWith('-----BEGIN PUBLIC KEY-----', $publicKey);
    }

    /** @test */
    public function it_can_write_keys_to_disk()
    {
        $privateKeyPath = $this->getTempPath('privateKey');
        $publicKeyPath = $this->getTempPath('publicKey');

        if (file_exists($privateKeyPath)) {
            unlink($privateKeyPath);
        }


        if (file_exists($publicKeyPath)) {
            unlink($publicKeyPath);
        }

        (new KeyPair())->generate(
            $privateKeyPath,
            $publicKeyPath
        );

        $this->assertStringStartsWith('-----BEGIN PRIVATE KEY-----', file_get_contents($privateKeyPath));
        $this->assertStringStartsWith('-----BEGIN PUBLIC KEY-----', file_get_contents($publicKeyPath));
    }

    /** @test */
    public function it_can_generate_a_password_protected_key()
    {
        $password = 'my-password';

        $generated = (new KeyPair())
            ->password('my-password')
            ->generate();
        $generatedPrivateKey = $generated[0];
        $privateKey = PrivateKey::fromString($generatedPrivateKey, $password);
        $this->assertInstanceOf(PrivateKey::class, $privateKey);

        $this->expectException(InvalidPrivateKey::class);
        PrivateKey::fromString($generatedPrivateKey, 'invalid-password');
    }
}
