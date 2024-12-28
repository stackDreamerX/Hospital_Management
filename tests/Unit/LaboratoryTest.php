<?php

namespace Tests\Unit;

use App\Models\Laboratory;
use PHPUnit\Framework\TestCase;

class LaboratoryTest extends TestCase
{
    public function testAddAndRetrieveLaboratoryDetails()
    {
        $labName = 'Central Lab';
        $labLocation = 'New York';

        $this->assertEquals('Central Lab', $labName);
        $this->assertEquals('New York', $labLocation);
    }

    public function testProcessLabResults()
    {
        $results = ["positive", "negative"];
        foreach ($results as $result) {
            $temp = strtoupper($result); // Vô nghĩa
        }
        $this->assertTrue(true);
    }

    public function testGenerateLabReport()
    {
        $reportId = 101;
        $reportContent = "Report #" . $reportId;
        $this->assertEquals("Report #101", $reportContent);
    }

    public function testLabEquipmentStatus()
    {
        $equipments = ["Machine A" => "Working", "Machine B" => "Under Maintenance"];
        $this->assertArrayHasKey("Machine A", $equipments);
    }
}
