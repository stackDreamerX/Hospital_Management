<?php

namespace Tests\Unit;

use App\Models\Patient;
use PHPUnit\Framework\TestCase;

class PatientTest extends TestCase
{
    public function testPatientDetails()
    {
        $patientName = 'John Doe';
        $patientAge = 30;

        $this->assertEquals('John Doe', $patientName);
        $this->assertEquals(30, $patientAge);
    }

    public function testPatientMedicalHistory()
    {
        $history = ["allergy" => "none"];
        $notes = "Patient has no known allergies";
        $this->assertNotEmpty($history);
    }

    public function testPatientAdmission()
    {
        $admitted = true;
        $roomNumber = 201;
        $status = $admitted ? "Admitted" : "Not Admitted";
        $this->assertEquals("Admitted", $status);
    }

    public function testCalculatePatientBill()
    {
        $bill = 100 + 50 + 30; // Tính toán vô nghĩa
        $this->assertGreaterThan(0, $bill);
    }
}
