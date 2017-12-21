<?php


namespace ZuluCrypto\StellarSdk\Test\Integration;


use ZuluCrypto\StellarSdk\Keypair;
use ZuluCrypto\StellarSdk\Test\Util\IntegrationTest;

class CreateAccountTest extends IntegrationTest
{
    /**
     * @group requires-integrationnet
     */
    public function testCreateAccount()
    {
        /** @var Keypair $sourceKeypair */
        $sourceKeypair = Keypair::newFromSeed($this->fixtureAccounts['basic1']['seed']);

        $newKeypair = Keypair::newFromRandom();

        $this->horizonServer->buildTransaction($sourceKeypair->getPublicKey())
            ->addCreateAccountOp($newKeypair->getAccountId(), 100)
            ->submit($sourceKeypair->getSecret());

        // Should then be able to retrieve the account and verify the balance
        $newAccount = $this->horizonServer->getAccount($newKeypair->getPublicKey());

        $this->assertEquals("100", $newAccount->getNativeBalance());
    }
}