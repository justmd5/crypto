<?php

namespace Justmd5\Crypto\Tests\Rsa;

use Justmd5\Crypto\Rsa\PrivateKey;
use Justmd5\Crypto\Rsa\PublicKey;
use Justmd5\Crypto\Tests\TestCase;

class KeyTest extends TestCase
{
    protected $privateKey;

    protected $publicKey;

    public function setUp()
    {
        $this->privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $this->publicKey = PublicKey::fromFile($this->getStub('publicKey'));
    }

    /** @test */
    public function a_private_key_can_be_used_to_encrypt_a_Data_that_can_be_decrypted_by_a_public_key()
    {
        $originalData = 'secret data';

        $encryptedData = $this->privateKey->encrypt($originalData);

        $this->assertNotEquals($originalData, $encryptedData);

        $decryptedData = $this->publicKey->decrypt($encryptedData);

        $this->assertEquals($decryptedData, $originalData);
    }

    /** @test */
    public function a_public_key_can_be_used_to_encrypt_a_Data_that_can_be_decrypted_by_a_private_key()
    {
        $originalData = 'secret data';

        $encryptedData = $this->publicKey->encrypt($originalData);

        $this->assertNotEquals($originalData, $encryptedData);

        $decryptedData = $this->privateKey->decrypt($encryptedData);

        $this->assertEquals($decryptedData, $originalData);
    }

    /** @test */
    public function it_can_sign_and_verify_a_message()
    {
        $signature = $this->privateKey->sign('my message');

        $this->assertTrue($this->publicKey->verify('my message', $signature));
        $this->assertFalse($this->publicKey->verify('my modified message', $signature));
        $this->assertFalse($this->publicKey->verify('my message', $signature . '- making the signature invalid'));
    }
}
