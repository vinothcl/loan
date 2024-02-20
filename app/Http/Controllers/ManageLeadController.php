<?php
namespace App\Http\Controllers;

use App\Helpers\ExportHelper;
use App\Models\Lead;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ManageLeadController extends Controller {
	public function index(Request $request) {
		$data['title'] = "Manage Lead";
		if (Auth::user()->is_admin) {
			$data['users'] = User::all();
			$data['created_by'] = $created_by = $request->created_by;
			$data['q'] = $q = $request->q;
			$data['date'] = $date = $request->date;
			if ($request->action && $request->action == 'export') {
				$rows = (new Lead)->getLeadListForexport($created_by, $q, $date);
				$excelData = array(
					'column' => ['Name', 'Email', 'Phone Number', 'Address', 'Req Type', 'Created By', 'Created At'],
					'rows' => $rows,
					'fileName' => 'Leads-Reports-' . date('d-m-y-H:i:s'),
					'type' => 'xls',
					'limit' => 5000,
				);
				return ExportHelper::exportRecordWithColumns($excelData);
			}
			return view('manage_lead.admin_index', $data);
		} else {
			return view('manage_lead.index', $data);
		}
	}
	public function getLeadListAjax(Request $request) {
		if (Auth::user()->is_admin) {
			$created_by = $request->created_by ? $request->created_by : '';
		} else {
			$created_by = Auth::id();
		}
		$data['q'] = $q = $request->q;
		$data['date'] = $date = $request->date;
		$leads = (new Lead)->getLeadListAjax($created_by, $q, $date);
		return datatables()->of($leads)
			->addColumn('action', function ($lead) {
				$action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="' . route('manage-lead-edit', $lead->id) . '"><i class="fas fa-pencil-alt"></i></a>';
				$action .= ' <a class="btn btn-danger btn-xs delete-lead" href="javascript:;" data-id="' . $lead->id . '" data-url="' . route('manage-lead-delete', $lead->id) . '"><i class="fas fa-trash"></i></a></div>';
				return $action;

			})->filter(function ($query) use ($request) {
			if ($request->has('search')) {
				$serchTerms = $request->search['value'];
				$query->where(function ($q) use ($serchTerms) {
					$q->where('leads.name', 'like', "%{$serchTerms}%")
						->orWhere('leads.email', 'like', "%{$serchTerms}%")
						->orWhere('leads.phone', 'like', "%{$serchTerms}%")
						->orWhere('leads.type', 'like', "%{$serchTerms}%")
						->orWhere('users.name', 'like', "%{$serchTerms}%")
						->orWhere('leads.address', 'like', "%{$serchTerms}%");
				});
			}
		})
			->rawColumns(['action', 'is_admin'])
			->make(true);
	}
	public function add(Request $request) {
		$data['title'] = "Manage Lead - Add";
		return view('manage_lead.add', $data);
	}
	public function edit(Request $request) {
		$id = $request->id;
		if (!$id) {
			$request->session()->flash('error', "Something Went Wrong!.");
			return redirect(route('manage-lead'))->withInput();
		}
		$data['info'] = $info = Lead::find($id);
		if (!$info) {
			$request->session()->flash('error', "Unable to find lead.");
			return redirect(route('manage-lead'))->withInput();
		}
		$data['title'] = "Manage Lead - Edit";
		return view('manage_lead.edit', $data);
	}
	public function save(Request $request) {
		$info['name'] = $request->name;
		$info['email'] = $request->email;
		$info['phone'] = $request->phone;
		$info['type'] = $request->type;
		$info['address'] = $request->address;
		if ((new Lead)->createLead($info)) {
			$request->session()->flash('success', "New Lead Created Successfully.");
			return redirect(route('manage-lead'));
		} else {
			$request->session()->flash('error', "Nothing to update (or) unable to update.");
			return redirect(route('manage-lead'))->withInput();
		}
	}
	public function update(Request $request) {
		$info['name'] = $request->name;
		$info['email'] = $request->email;
		$info['phone'] = $request->phone;
		$info['type'] = $request->type;
		$info['address'] = $request->address;
		$info['id'] = $request->id;
		if ((new Lead)->updateLead($info)) {
			$request->session()->flash('success', "Lead Updated Successfully.");
			return redirect(route('manage-lead'));
		} else {
			$request->session()->flash('error', "Nothing to update (or) unable to update.");
			return redirect(route('manage-lead'))->withInput();
		}
	}
	public function delete(Request $request) {
		$id = $request->id;
		if ($id) {
			return (new Lead)->deleteLead($id);
		} else {
			return false;
		}

	}
}