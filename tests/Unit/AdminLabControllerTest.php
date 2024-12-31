<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Laboratory;
use App\Models\LaboratoryType;

class AdminLabControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the application returns a successful response for the lab list page.
     */
    public function test_it_displays_laboratory_list(): void
    {
        // Create dummy laboratory types
        LaboratoryType::factory()->count(3)->create();

        // Send a GET request to the laboratory list route
        $response = $this->get('/admin/laboratories');

        // Assert that the response is successful
        $response->assertStatus(200);
        $response->assertViewIs('admin.laboratories.index'); // Ensure the correct view is used
        $response->assertViewHas('labTypes'); // Ensure 'labTypes' is passed to the view
    }

    /**
     * Test if a new laboratory can be created successfully.
     */
    public function test_it_creates_a_new_laboratory(): void
    {
        // Simulate an admin user
        $admin = User::factory()->create(['is_admin' => true]);

        // Log in as admin
        $this->actingAs($admin);

        // Dummy data for a new laboratory
        $data = [
            'name' => 'New Lab',
            'type_id' => 1, // ID of the laboratory type
            'location' => 'Main Campus',
        ];

        // Send a POST request to create a laboratory
        $response = $this->post('/admin/laboratories', $data);

        // Assert that the response redirects correctly
        $response->assertRedirect('/admin/laboratories');

        // Assert that the database has the new laboratory
        $this->assertDatabaseHas('laboratories', $data);
    }

    /**
     * Test if an existing laboratory can be updated successfully.
     */
    public function test_it_updates_a_laboratory(): void
    {
        // Create a dummy laboratory
        $lab = Laboratory::factory()->create();

        // Dummy data for updating the laboratory
        $updateData = ['name' => 'Updated Lab Name'];

        // Send a PUT request to update the laboratory
        $response = $this->put("/admin/laboratories/{$lab->id}", $updateData);

        // Assert that the response redirects correctly
        $response->assertRedirect('/admin/laboratories');

        // Assert that the database has the updated data
        $this->assertDatabaseHas('laboratories', $updateData);
    }

    /**
     * Test if a laboratory can be deleted successfully.
     */
    public function test_it_deletes_a_laboratory(): void
    {
        // Create a dummy laboratory
        $lab = Laboratory::factory()->create();

        // Send a DELETE request to delete the laboratory
        $response = $this->delete("/admin/laboratories/{$lab->id}");

        // Assert that the response redirects correctly
        $response->assertRedirect('/admin/laboratories');

        // Assert that the laboratory no longer exists in the database
        $this->assertDatabaseMissing('laboratories', ['id' => $lab->id]);
    }
}
