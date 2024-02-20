<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Lead extends Authenticatable {
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'phone',
		'type',
		'address',
		'created_by',
		'created_at',
		'updated_at',
	];

	public $timestamps = true;

	protected $primaryKey = 'id';

	public function getLeadListAjax($created_by, $q = "", $date = "") {
		$query = $this->select('leads.*', 'users.name as ename')->leftjoin('users', 'users.id', 'leads.created_by');

		if ($q) {
			$query->whereRaw("(leads.name like '%$q%' or leads.email like '%$q%'  or leads.phone like '%$q%'  or leads.type like '%$q%' or leads.address like '%$q%' or users.name like '%$q%' )");
		}
		if ($created_by) {
			$query->where('created_by', $created_by);
		}
		if ($date) {
			$my = explode('-', $date);
			$query->whereRaw("(YEAR(leads.created_at) = $my[0] AND MONTH(leads.created_at) = $my[1])");
		}
		return $query;

	}
	public function getLeadListForexport($created_by, $q = "", $date = "") {
		$query = $this->select('leads.name', 'leads.email', 'leads.phone', 'leads.address', 'leads.type', 'users.name as ename', DB::RAW("DATE_FORMAT(leads.created_at,'%d-%m-%Y')"))->leftjoin('users', 'users.id', 'leads.created_by');

		if ($q) {
			$query->whereRaw("(leads.name like '%$q%' or leads.email like '%$q%'  or leads.phone like '%$q%'  or leads.type like '%$q%' or leads.address like '%$q%' or users.name like '%$q%' )");
		}
		if ($created_by) {
			$query->where('created_by', $created_by);
		}
		if ($date) {
			$my = explode('-', $date);
			$query->whereRaw("(YEAR(leads.created_at) = $my[0] AND MONTH(leads.created_at) = $my[1])");
		}
		return $query->get()->toArray();

	}

	public function createLead($info) {
		return $this->create([
			'name' => $info['name'],
			'email' => $info['email'],
			'phone' => $info['phone'],
			'type' => $info['type'],
			'address' => $info['address'],
			'created_by' => Auth::id(),
		]);
	}
	public function updateLead($info) {
		$data = [
			'name' => $info['name'],
			'email' => $info['email'],
			'phone' => $info['phone'],
			'type' => $info['type'],
			'address' => $info['address'],
		];
		return $this->where('id', $info['id'])->update($data);
	}
	public function deleteLead($id) {
		return $this->where('id', $id)->delete();
	}

}
