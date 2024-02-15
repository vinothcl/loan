<?php
namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\User;
use Illuminate\Http\Request;

class ManageEmployeeController extends Controller {
	public function index(Request $request) {
		$data['title'] = "Manage Employee";
		return view('manage_employee.index', $data);
	}
	public function getEmployeeListAjax(Request $request) {
		return datatables()->of((new User)->getEmployeeListAjax())
			->addColumn('action', function ($employee) {
				$action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="' . route('manage-employee-edit', $employee->id) . '"><i class="fas fa-pencil-alt"></i></a>';
				$action .= ' <a class="btn btn-danger btn-xs delete-employee" href="javascript:;" data-id="' . $employee->id . '" data-url="' . route('manage-employee-delete', $employee->id) . '"><i class="fas fa-trash"></i></a></div>';
				return $action;
			})
			->rawColumns(['action'])
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
		$info['name'] = $request->name;
		$info['status'] = $request->status;
		$info['order_by'] = $request->order_by ? $request->order_by : 15;
		$info['employee_pic'] = '';
		if ($request->employee_pic) {
			$directory = public_path() . '/employee_pic';
			if (!is_dir($directory)) {
				mkdir($directory);
				chmod($directory, 0777);
			}
			$imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('employee_pic')->getClientOriginalName());
			$newName = CommonHelper::getUrlFriendlyString($info['name'] . ' ' . strtotime(date('Y-m-d H:i:s')));
			$imageName = $newName . '.' . $request->file('employee_pic')->getClientOriginalExtension();
			$request->file('employee_pic')->move($directory, $imageName);
			$info['employee_pic'] = 'employee_pic/' . $imageName;
		}
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
		$info['status'] = $request->status;
		$info['order_by'] = $request->order_by ? $request->order_by : 15;
		$info['id'] = $request->id;
		$info['employee_pic'] = '';
		if ($request->employee_pic) {
			$directory = public_path() . '/employee_pic';
			if (!is_dir($directory)) {
				mkdir($directory);
				chmod($directory, 0777);
			}
			$imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('employee_pic')->getClientOriginalName());
			$request->file('employee_pic')->move($directory, $imageName);
			$info['employee_pic'] = 'employee_pic/' . $imageName;
		}

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