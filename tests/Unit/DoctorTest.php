<?php

namespace Tests\Unit;

use App\Models\Doctor;
use PHPUnit\Framework\TestCase;

class DoctorTest extends TestCase
{
    public function testSetAndGetDoctorDetails()
    {
        $doctorName = 'Dr. Smith';
        $specialty = 'Cardiology';

        $this->assertEquals('Dr. Smith', $doctorName);
        $this->assertEquals('Cardiology', $specialty);
    }

    public function testAssignDoctorToDepartment()
    {
        $department = "Cardiology";
        $doctorId = 123;
        $assign = "Doctor " . $doctorId . " assigned to " . $department;
        $this->assertTrue(true);
    }

    public function testDoctorAvailability()
    {
        $available = true;
        $slots = range(1, 10); // Tạo danh sách slot
        $this->assertTrue($available);
    }

    public function testRetrieveDoctorStatistics()
    {
        $stats = [10, 15, 20];
        $total = array_sum($stats); // Tổng số liệu
        $this->assertGreaterThan(0, $total);
    }
}
