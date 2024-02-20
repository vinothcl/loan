<?php
namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ManageEmployeeController extends Controller {
	public function index(Request $request) {
		$data['title'] = "Manage Employee";
		return view('manage_employee.index', $data);
	}
	public function getEmployeeListAjax(Request $request) {
		return datatables()->of((new User)->getEmployeeListAjax())
			->addColumn('action', function ($employee) {
				if (Auth::id() == $employee->id) {
					$action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="' . route('manage-employee-edit', $employee->id) . '"><i class="fas fa-times-circle"></i></a>';
					$action .= ' <a class="btn btn-danger btn-xs" href="javascript:;"><i class="fas fa-times-circle"></i></a></div>';
					return $action;
				}
				$action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="' . route('manage-employee-edit', $employee->id) . '"><i class="fas fa-pencil-alt"></i></a>';
				$action .= ' <a class="btn btn-danger btn-xs delete-employee" href="javascript:;" data-id="' . $employee->id . '" data-url="' . route('manage-employee-delete', $employee->id) . '"><i class="fas fa-trash"></i></a></div>';
				return $action;

			})
			->addColumn('is_admin', function ($employee) {
				if ($employee->is_admin == '1' || $employee->is_admin == 1) {
					return 'Yes';
				}
				return 'No';
			})
			->rawColumns(['action', 'is_admin'])
			->make(true);
	}
	public function add(Request $request) {
		$data['title'] = "Manage Employee - Add";
		return view('manage_employee.add', $data);
	}
	public function edit(Request $request) {
		$id = $request->id;
		if (!$id) {
			$request->session()->flash('error', "Something Went Wrong!.");
			return redirect(route('manage-employee'))->withInput();
		}
		$data['info'] = $info = User::find($id);
		if (!$info) {
			$request->session()->flash('error', "Unable to find employee.");
			return redirect(route('manage-employee'))->withInput();
		}
		$data['title'] = "Manage Employee - Edit";
		return view('manage_employee.edit', $data);
	}
	public function save(Request $request) {
		if (User::where('email', $request->name)->first()) {
			$request->session()->flash('error', "Email already exists!.");
			return redirect(route('manage-employee'));
		}
		$info['name'] = $request->name;
		$info['email'] = $request->email;
		$info['password'] = $request->password;
		$info['is_admin'] = $request->is_admin ? $request->is_admin : '0';
		if ((new User)->createEmployee($info)) {
			$request->session()->flash('success', "New Employee Created Successfully.");
			return redirect(route('manage-employee'));
		} else {
			$request->session()->flash('error', "Nothing to update (or) unable to update.");
			return redirect(route('manage-employee'))->withInput();
		}
	}
	public function update(Request $request) {
		$info['name'] = $request->name;
		$info['email'] = $request->email;
		$info['password'] = $request->password;
		$info['is_admin'] = $request->is_admin ? $request->is_admin : '0';
		$info['id'] = $request->id;
		if ((new User)->updateEmployee($info)) {
			$request->session()->flash('success', "Employee Updated Successfully.");
			return redirect(route('manage-employee'));
		} else {
			$request->session()->flash('error', "Nothing to update (or) unable to update.");
			return redirect(route('manage-employee'))->withInput();
		}
	}
	public function delete(Request $request) {
		$id = $request->id;
		if ($id) {
			return (new User)->deleteEmployee($id);
		} else {
			return false;
		}

	}
}