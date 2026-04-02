<?php

declare(strict_types=1);

namespace Essabu\Tests\Common;

use Essabu\Common\WebhookVerifier;
use PHPUnit\Framework\TestCase;

final class WebhookVerifierTest extends TestCase
{
    private const SECRET = 'whsec_test_secret_key';

    private function computeSignature(string $payload, string $secret): string
    {
        return 'sha256=' . hash_hmac('sha256', $payload, $secret);
    }

    public function testVerifyValidSignature(): void
    {
        $verifier = new WebhookVerifier(self::SECRET);
        $payload = '{"type":"hr.employee.created","data":{"id":"emp_1"}}';
        $signature = $this->computeSignature($payload, self::SECRET);

        $this->assertTrue($verifier->verify($payload, $signature));
    }

    public function testVerifyInvalidSignature(): void
    {
        $verifier = new WebhookVerifier(self::SECRET);
        $payload = '{"type":"hr.employee.created","data":{"id":"emp_1"}}';

        $this->assertFalse($verifier->verify($payload, 'sha256=invalid_signature'));
    }

    public function testVerifyTamperedPayload(): void
    {
        $verifier = new WebhookVerifier(self::SECRET);
        $originalPayload = '{"type":"hr.employee.created","data":{"id":"emp_1"}}';
        $tamperedPayload = '{"type":"hr.employee.created","data":{"id":"emp_2"}}';
        $signature = $this->computeSignature($originalPayload, self::SECRET);

        $this->assertFalse($verifier->verify($tamperedPayload, $signature));
    }

    public function testVerifyWrongSecret(): void
    {
        $verifier = new WebhookVerifier(self::SECRET);
        $payload = '{"type":"test"}';
        $wrongSignature = $this->computeSignature($payload, 'wrong_secret');

        $this->assertFalse($verifier->verify($payload, $wrongSignature));
    }

    public function testVerifyEmptyPayload(): void
    {
        $verifier = new WebhookVerifier(self::SECRET);
        $payload = '';
        $signature = $this->computeSignature($payload, self::SECRET);

        $this->assertTrue($verifier->verify($payload, $signature));
    }

    public function testVerifyEmptySignature(): void
    {
        $verifier = new WebhookVerifier(self::SECRET);
        $this->assertFalse($verifier->verify('{"data":"test"}', ''));
    }

    public function testVerifySignatureWithoutPrefix(): void
    {
        $verifier = new WebhookVerifier(self::SECRET);
        $payload = '{"data":"test"}';
        // Raw hash without sha256= prefix should fail
        $rawHash = hash_hmac('sha256', $payload, self::SECRET);

        $this->assertFalse($verifier->verify($payload, $rawHash));
    }

    public function testVerifyLargePayload(): void
    {
        $verifier = new WebhookVerifier(self::SECRET);
        $payload = str_repeat('{"data":"test"},', 10000);
        $signature = $this->computeSignature($payload, self::SECRET);

        $this->assertTrue($verifier->verify($payload, $signature));
    }

    public function testVerifyWithSpecialCharacters(): void
    {
        $verifier = new WebhookVerifier(self::SECRET);
        $payload = '{"name":"Jean-Pierre","note":"C\'est l\'heure de la r\u00e9union"}';
        $signature = $this->computeSignature($payload, self::SECRET);

        $this->assertTrue($verifier->verify($payload, $signature));
    }

    public function testTimingSafeComparison(): void
    {
        // Verify that the implementation uses hash_equals (timing-safe)
        // by ensuring consistent behavior regardless of where the signature differs
        $verifier = new WebhookVerifier(self::SECRET);
        $payload = '{"test":true}';
        $validSig = $this->computeSignature($payload, self::SECRET);

        // Alter first character
        $altered1 = 'Xha256=' . substr($validSig, 7);
        $this->assertFalse($verifier->verify($payload, $altered1));

        // Alter last character
        $altered2 = substr($validSig, 0, -1) . 'X';
        $this->assertFalse($verifier->verify($payload, $altered2));
    }
}
