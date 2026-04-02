<?php

declare(strict_types=1);

namespace Essabu\Tests\Identity;

use Essabu\Common\HttpClient;
use Essabu\Identity\Api\AuthApi;
use Essabu\Identity\IdentityClient;
use PHPUnit\Framework\TestCase;

final class AuthApiTest extends TestCase
{
    private HttpClient $httpMock;
    private AuthApi $api;

    protected function setUp(): void
    {
        $this->httpMock = $this->createMock(HttpClient::class);
        $this->api = new AuthApi($this->httpMock);
    }

    public function testLogin(): void
    {
        $credentials = [
            'email'    => 'user@example.com',
            'password' => 'secret',
        ];
        $expected = [
            'accessToken'  => 'eyJ...',
            'refreshToken' => 'ref_...',
            'expiresIn'    => 3600,
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/auth/login', $credentials)
            ->willReturn($expected);

        $result = $this->api->login($credentials);

        $this->assertArrayHasKey('accessToken', $result);
        $this->assertArrayHasKey('refreshToken', $result);
        $this->assertSame(3600, $result['expiresIn']);
    }

    public function testRegister(): void
    {
        $data = [
            'email'     => 'new@example.com',
            'password'  => 'SecureP@ss1',
            'firstName' => 'Alice',
            'lastName'  => 'Doe',
        ];
        $expected = [
            'userId' => 'usr_new1',
            'email'  => 'new@example.com',
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/auth/register', $data)
            ->willReturn($expected);

        $result = $this->api->register($data);

        $this->assertSame('usr_new1', $result['userId']);
    }

    public function testRefresh(): void
    {
        $expected = [
            'accessToken'  => 'eyJ_new...',
            'refreshToken' => 'ref_new...',
            'expiresIn'    => 3600,
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/auth/refresh', ['refresh_token' => 'old_refresh_token'])
            ->willReturn($expected);

        $result = $this->api->refresh('old_refresh_token');

        $this->assertArrayHasKey('accessToken', $result);
    }

    public function testLogout(): void
    {
        $expected = ['success' => true];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/auth/logout')
            ->willReturn($expected);

        $result = $this->api->logout();

        $this->assertTrue($result['success']);
    }

    public function testVerifyEmail(): void
    {
        $expected = ['verified' => true];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/auth/verify-email', ['token' => 'verify_abc123'])
            ->willReturn($expected);

        $result = $this->api->verifyEmail('verify_abc123');

        $this->assertTrue($result['verified']);
    }

    public function testResetPassword(): void
    {
        $expected = ['sent' => true];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/auth/reset-password', ['email' => 'user@example.com'])
            ->willReturn($expected);

        $result = $this->api->resetPassword('user@example.com');

        $this->assertTrue($result['sent']);
    }

    public function testEnable2fa(): void
    {
        $expected = [
            'secret'    => 'JBSWY3DPEHPK3PXP',
            'qrCodeUrl' => 'otpauth://totp/Essabu:user@example.com?secret=...',
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/auth/2fa/enable')
            ->willReturn($expected);

        $result = $this->api->enable2fa();

        $this->assertArrayHasKey('secret', $result);
        $this->assertArrayHasKey('qrCodeUrl', $result);
    }

    // ---------------------------------------------------------------
    // IdentityClient accessor
    // ---------------------------------------------------------------

    public function testIdentityClientExposesAuthApi(): void
    {
        $client = new IdentityClient($this->httpMock);
        $this->assertInstanceOf(AuthApi::class, $client->auth);
    }

    public function testIdentityClientCachesAuthApi(): void
    {
        $client = new IdentityClient($this->httpMock);
        $this->assertSame($client->auth, $client->auth);
    }
}
