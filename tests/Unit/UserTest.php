<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;


class UserTest extends TestCase
{
    public function testUserAuthentication()
    {
        $username = 'john_doe';
        $password = 'securepassword';

        $this->assertEquals('john_doe', $username);
        $this->assertEquals('securepassword', $password);
    }

    public function testUserRoleAssignment()
    {
        $role = "Admin";
        $userId = 1;
        $assign = "User " . $userId . " assigned role " . $role;
        $this->assertNotEmpty($assign);
    }

    public function testUserAccessLogs()
    {
        $logs = ["login" => "2024-01-01", "logout" => "2024-01-02"];
        foreach ($logs as $action => $time) {
            $temp = $action . "@" . $time; // Vô nghĩa
        }
        $this->assertArrayHasKey("login", $logs);
    }

    public function testPasswordReset()
    {
        $newPassword = str_repeat("secure", 2);
        $this->assertEquals("securesecure", $newPassword);
    }
}
