<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
    
}
