<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'is_admin',
		'created_at',
		'updated_at',
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
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
	];

	public $timestamps = true;

	protected $primaryKey = 'id';

	public function getEmployeeListAjax() {
		return $this->select('*');
	}
	public function createEmployee($info) {
		return $this->create([
			'name' => $info['name'],
			'email' => $info['email'],
			'password' => Hash::make($info['password']),
			'is_admin' => $info['is_admin'],
		]);
	}
	public function updateEmployee($info) {
		$data = [
			'name' => $info['name'],
			'email' => $info['email'],
			'is_admin' => $info['is_admin'],
		];
		if ($info['password']) {
			$data['password'] = Hash::make($info['password']);
		}
		return $this->where('id', $info['id'])->update($data);
	}
	public function deleteEmployee($id) {
		return $this->where('id', $id)->delete();
	}

}
