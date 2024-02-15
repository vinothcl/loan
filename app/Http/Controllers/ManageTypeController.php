<?php
namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\Type;
use Illuminate\Http\Request;

class ManageTypeController extends Controller {
	public function index(Request $request) {
		$data['title'] = "Manage Type";
		return view('manage_type.index', $data);
	}
	public function getTypeListAjax(Request $request) {
		return datatables()->of((new Type)->getTypeListAjax())
			->addColumn('action', function ($type) {
				$action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="' . route('manage-type-edit', $type->id) . '"><i class="fas fa-pencil-alt"></i></a>';
				$action .= ' <a class="btn btn-danger btn-xs delete-type" href="javascript:;" data-id="' . $type->id . '" data-url="' . route('manage-type-delete', $type->id) . '"><i class="fas fa-trash"></i></a></div>';
				return $action;
			})
			->addColumn('image', function ($type) {
				$image = $type->image ? $type->image : asset("type_pic/default.jpg");
				return '<img src="' . asset($image) . '" class="img-responsive" width="150px" height="50px">';
			})
			->editColumn('status', function ($type) {
				if ($type->status == 1) {
					return "Active";
				} else {
					return "Inactive";
				}

			})
			->rawColumns(['action', 'image', 'status'])
			->make(true);
	}
	public function add(Request $request) {
		$data['title'] = "Manage Type - Add";
		return view('manage_type.add', $data);
	}
	public function edit(Request $request) {
		$id = $request->id;
		if (!$id) {
			$request->session()->flash('error', "Something Went Wrong!.");
			return redirect(route('manage-type'))->withInput();
		}
		$data['info'] = $info = Type::find($id);
		if (!$info) {
			$request->session()->flash('error', "Unable to find type.");
			return redirect(route('manage-type'))->withInput();
		}
		$data['title'] = "Manage Type - Edit";
		return view('manage_type.edit', $data);
	}
	public function save(Request $request) {
		$info['name'] = $request->name;
		$info['status'] = $request->status;
		$info['order_by'] = $request->order_by ? $request->order_by : 15;
		$info['type_pic'] = '';
		if ($request->type_pic) {
			$directory = public_path() . '/type_pic';
			if (!is_dir($directory)) {
				mkdir($directory);
				chmod($directory, 0777);
			}
			$imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('type_pic')->getClientOriginalName());
			$newName = CommonHelper::getUrlFriendlyString($info['name'] . ' ' . strtotime(date('Y-m-d H:i:s')));
			$imageName = $newName . '.' . $request->file('type_pic')->getClientOriginalExtension();
			$request->file('type_pic')->move($directory, $imageName);
			$info['type_pic'] = 'type_pic/' . $imageName;
		}
		if ((new Type)->createType($info)) {
			$request->session()->flash('success', "New Type Created Successfully.");
			return redirect(route('manage-type'));
		} else {
			$request->session()->flash('error', "Nothing to update (or) unable to update.");
			return redirect(route('manage-type'))->withInput();
		}
	}
	public function update(Request $request) {
		$info['name'] = $request->name;
		$info['status'] = $request->status;
		$info['order_by'] = $request->order_by ? $request->order_by : 15;
		$info['id'] = $request->id;
		$info['type_pic'] = '';
		if ($request->type_pic) {
			$directory = public_path() . '/type_pic';
			if (!is_dir($directory)) {
				mkdir($directory);
				chmod($directory, 0777);
			}
			$imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('type_pic')->getClientOriginalName());
			$request->file('type_pic')->move($directory, $imageName);
			$info['type_pic'] = 'type_pic/' . $imageName;
		}

		if ((new Type)->updateType($info)) {
			$request->session()->flash('success', "Type Updated Successfully.");
			return redirect(route('manage-type'));
		} else {
			$request->session()->flash('error', "Nothing to update (or) unable to update.");
			return redirect(route('manage-type'))->withInput();
		}
	}
	public function delete(Request $request) {
		$id = $request->id;
		if ($id) {
			return (new Type)->deleteType($id);
		} else {
			return false;
		}

	}
}