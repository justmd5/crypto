<?php

namespace Justmd5\Crypto\Tests\Rsa;

use Justmd5\Crypto\Rsa\Exceptions\CouldNotDecryptData;
use Justmd5\Crypto\Rsa\Exceptions\InvalidPrivateKey;
use Justmd5\Crypto\Rsa\PrivateKey;
use Justmd5\Crypto\Rsa\PublicKey;
use Justmd5\Crypto\Tests\TestCase;

class PrivateKeyTest extends TestCase
{
    /** @test */
    public function the_private_key_class_can_detect_invalid_data()
    {
        $originalData = 'secret data';
        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));
        $encryptedData = $publicKey->encrypt($originalData);
        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $modifiedDecrypted = $encryptedData . 'modified';
        $this->assertFalse($privateKey->canDecrypt($modifiedDecrypted));

        $this->expectException(CouldNotDecryptData::class);
        $privateKey->decrypt($modifiedDecrypted);
    }

    /** @test */
    public function it_can_get_the_details_of_the_private_key()
    {
        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $details = $privateKey->details();

        $this->assertIsArray($details);
    }

    /** @test */
    public function a_private_key_will_throw_an_exception_if_it_is_invalid()
    {
        $this->expectException(InvalidPrivateKey::class);

        PrivateKey::fromString('invalid-key');
    }

    protected function assertIsArray($detail, $message = '')
    {
        static::assertThat(
            $detail,
            $this->IsType('array'),
            $message
        );
    }
}
