<?php

declare(strict_types=1);

namespace Essabu\Tests\Hr;

use Essabu\Common\HttpClient;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;
use Essabu\Hr\Api\EmployeeApi;
use Essabu\Hr\HrClient;
use PHPUnit\Framework\TestCase;

final class EmployeeApiTest extends TestCase
{
    private HttpClient $httpMock;
    private EmployeeApi $api;

    protected function setUp(): void
    {
        $this->httpMock = $this->createMock(HttpClient::class);
        $this->api = new EmployeeApi($this->httpMock);
    }

    // ---------------------------------------------------------------
    // CRUD operations
    // ---------------------------------------------------------------

    public function testCreateEmployee(): void
    {
        $input = [
            'firstName'    => 'Jean',
            'lastName'     => 'Mukendi',
            'email'        => 'jean@example.com',
            'departmentId' => 'dept_1',
        ];
        $expected = array_merge(['id' => 'emp_abc123'], $input);

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/api/hr/employees', $input)
            ->willReturn($expected);

        $result = $this->api->create($input);

        $this->assertSame('emp_abc123', $result['id']);
        $this->assertSame('Jean', $result['firstName']);
    }

    public function testGetEmployee(): void
    {
        $expected = [
            'id' => 'emp_abc123',
            'firstName' => 'Jean',
            'lastName' => 'Mukendi',
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/hr/employees/emp_abc123')
            ->willReturn($expected);

        $result = $this->api->get('emp_abc123');

        $this->assertSame('emp_abc123', $result['id']);
    }

    public function testListEmployeesWithoutPagination(): void
    {
        $responseData = [
            'content' => [['id' => 'emp_1'], ['id' => 'emp_2']],
            'page' => 0,
            'size' => 20,
            'totalElements' => 2,
            'totalPages' => 1,
            'first' => true,
            'last' => true,
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/hr/employees')
            ->willReturn($responseData);

        $result = $this->api->list();

        $this->assertInstanceOf(PageResponse::class, $result);
        $this->assertCount(2, $result->content);
        $this->assertSame(2, $result->totalElements);
    }

    public function testListEmployeesWithPagination(): void
    {
        $page = PageRequest::of(1, 10);
        $responseData = [
            'content' => [['id' => 'emp_11']],
            'page' => 1,
            'size' => 10,
            'totalElements' => 11,
            'totalPages' => 2,
            'first' => false,
            'last' => true,
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/hr/employees?page=1&size=10')
            ->willReturn($responseData);

        $result = $this->api->list($page);

        $this->assertSame(1, $result->page);
        $this->assertTrue($result->hasPrevious());
        $this->assertFalse($result->hasNext());
    }

    public function testUpdateEmployee(): void
    {
        $updateData = ['phone' => '+243 999 999 999'];
        $expected = [
            'id' => 'emp_abc123',
            'phone' => '+243 999 999 999',
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('put')
            ->with('/api/hr/employees/emp_abc123', $updateData)
            ->willReturn($expected);

        $result = $this->api->update('emp_abc123', $updateData);

        $this->assertSame('+243 999 999 999', $result['phone']);
    }

    public function testDeleteEmployee(): void
    {
        $this->httpMock
            ->expects($this->once())
            ->method('delete')
            ->with('/api/hr/employees/emp_abc123');

        $this->api->delete('emp_abc123');
    }

    // ---------------------------------------------------------------
    // Nested resources
    // ---------------------------------------------------------------

    public function testGetLeaveBalances(): void
    {
        $expected = [
            ['leaveType' => 'annual', 'remaining' => 15, 'total' => 20],
            ['leaveType' => 'sick', 'remaining' => 8, 'total' => 10],
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/hr/employees/emp_abc123/leave-balance')
            ->willReturn($expected);

        $result = $this->api->getLeaveBalances('emp_abc123');

        $this->assertCount(2, $result);
        $this->assertSame('annual', $result[0]['leaveType']);
    }

    public function testGetHistory(): void
    {
        $expected = [
            ['type' => 'promotion', 'date' => '2026-01-01'],
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/hr/employees/emp_abc123/history')
            ->willReturn($expected);

        $result = $this->api->getHistory('emp_abc123');

        $this->assertCount(1, $result);
    }

    public function testGetDocuments(): void
    {
        $expected = [
            ['id' => 'doc_1', 'name' => 'contract.pdf'],
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/hr/employees/emp_abc123/documents')
            ->willReturn($expected);

        $result = $this->api->getDocuments('emp_abc123');

        $this->assertCount(1, $result);
        $this->assertSame('contract.pdf', $result[0]['name']);
    }

    public function testGetOrgTree(): void
    {
        $expected = [
            ['id' => 'emp_manager', 'level' => 1],
            ['id' => 'emp_abc123', 'level' => 2],
        ];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/hr/employees/emp_abc123/org-tree')
            ->willReturn($expected);

        $result = $this->api->getOrgTree('emp_abc123');

        $this->assertCount(2, $result);
    }

    public function testGetOrgChart(): void
    {
        $expected = ['root' => ['id' => 'emp_ceo', 'children' => []]];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/hr/employees/org-chart')
            ->willReturn($expected);

        $result = $this->api->getOrgChart();

        $this->assertArrayHasKey('root', $result);
    }

    // ---------------------------------------------------------------
    // HrClient accessor
    // ---------------------------------------------------------------

    public function testHrClientExposesEmployeeApi(): void
    {
        $hrClient = new HrClient($this->httpMock);

        $this->assertInstanceOf(EmployeeApi::class, $hrClient->employees);
    }

    public function testHrClientCachesEmployeeApi(): void
    {
        $hrClient = new HrClient($this->httpMock);

        $first = $hrClient->employees;
        $second = $hrClient->employees;

        $this->assertSame($first, $second);
    }

    public function testHrClientUnknownApiThrows(): void
    {
        $hrClient = new HrClient($this->httpMock);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown HR API: nonExistent');
        $hrClient->nonExistent;
    }
}
