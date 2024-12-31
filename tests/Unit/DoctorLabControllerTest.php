<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Laboratory;
use App\Models\LaboratoryType;

class DoctorLabControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching lab list with all required data.
     */
    public function test_fetch_lab_list(): void
    {
        // Create dummy data
        LaboratoryType::factory()->count(3)->create();
        Laboratory::factory()->count(5)->create();

        // Send request to the lab list route
        $response = $this->get('/doctor/lab');

        // Assert the response is successful
        $response->assertStatus(200)
            ->assertViewIs('doctor.lab')
            ->assertViewHasAll([
                'labTypes',
                'laboratories',
                'doctors',
                'patients',
                'totalTests',
                'pendingTests',
                'completedTests',
                'totalRevenue',
            ]);
    }

    /**
     * Test storing a new lab assignment.
     */
    public function test_store_lab_assignment(): void
    {
        // Create necessary data
        $labType = LaboratoryType::factory()->create();
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create();

        // Data for creating a new laboratory assignment
        $data = [
            'lab_type' => $labType->LaboratoryTypeID,
            'user_id' => $user->UserID,
            'doctor_id' => $doctor->DoctorID,
            'lab_date' => '2024-01-01',
            'lab_time' => '09:00 AM',
            'price' => 500.0,
        ];

        // Send POST request
        $response = $this->postJson('/doctor/lab/store', $data);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson(['message' => 'Laboratory assignment created successfully']);

        // Assert the database contains the new lab assignment
        $this->assertDatabaseHas('laboratories', [
            'LaboratoryTypeID' => $data['lab_type'],
            'UserID' => $data['user_id'],
            'DoctorID' => $data['doctor_id'],
            'LaboratoryDate' => $data['lab_date'],
            'TotalPrice' => $data['price'],
        ]);
    }

    /**
     * Test updating a lab assignment.
     */
    public function test_update_lab_assignment(): void
    {
        // Create dummy data
        $lab = Laboratory::factory()->create();

        // Data for updating the lab
        $updateData = [
            'lab_type' => $lab->LaboratoryTypeID,
            'patient_id' => $lab->UserID,
            'doctor_id' => $lab->DoctorID,
            'lab_date' => '2024-01-02',
            'lab_time' => '10:00 AM',
            'price' => 600.0,
            'status' => 'Completed',
        ];

        // Send PUT request
        $response = $this->putJson("/doctor/lab/update/{$lab->id}", $updateData);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson(['message' => 'Lab test updated successfully']);

        // Assert the database contains the updated data
        $this->assertDatabaseHas('laboratories', [
            'LaboratoryDate' => $updateData['lab_date'],
            'LaboratoryTime' => $updateData['lab_time'],
            'TotalPrice' => $updateData['price'],
            'Status' => $updateData['status'],
        ]);
    }

    /**
     * Test deleting a lab assignment.
     */
    public function test_delete_lab_assignment(): void
    {
        // Create a dummy lab
        $lab = Laboratory::factory()->create();

        // Send DELETE request
        $response = $this->deleteJson("/doctor/lab/destroy/{$lab->id}");

        // Assert the response
        $response->assertStatus(200)
            ->assertJson(['message' => 'Lab test deleted successfully']);

        // Assert the database no longer has the lab
        $this->assertDatabaseMissing('laboratories', ['id' => $lab->id]);
    }

    /**
     * Test showing a lab assignment detail.
     */
    public function test_show_lab_assignment_detail(): void
    {
        // Create dummy data
        $lab = Laboratory::factory()->create();

        // Send GET request
        $response = $this->getJson("/doctor/lab/show/{$lab->id}");

        // Assert the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'labType',
                'patientName',
                'doctorName',
                'labDate',
                'labTime',
                'price',
                'result',
            ]);
    }
}
