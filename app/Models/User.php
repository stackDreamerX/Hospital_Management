<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'UserID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'RoleID',
        'username',
        'FullName',
        'Email',
        'password',
        'PhoneNumber',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'RoleID' => 'string', // Cast RoleID thành chuỗi
        ];
    }
    public function getAuthIdentifierName()
    {
        // return 'username'; // Tên cột bạn muốn dùng
        return 'UserID'; // Xác định khóa chính cho Laravel Guard
    }
    public function getAuthPassword()
    {
        return $this->password; // Laravel sẽ dùng cột "password" cho xác thực
    }  

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'UserID', 'UserID');
    }

    public function laboratories()
    {
        return $this->hasMany(Laboratory::class, 'UserID', 'UserID');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'UserID', 'UserID');
    }

    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'UserID', 'UserID');
    }

    /**
     * Get ratings submitted by this user
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id', 'UserID');
    }
    
    /**
     * Get doctor profile if user is a doctor
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'UserID', 'UserID');
    }
}
