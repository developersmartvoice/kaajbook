<?php

namespace Modules\Helper\Repositories;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Defect\Models\Defect;
use Modules\Estimate\Models\Estimate;
use Modules\Incident\Models\Incident;
use Modules\Invoice\Models\Invoice;
use Modules\Leave\Models\Leave;
use Modules\Payment\Models\Payment;
use Modules\Projects\Models\Project;
use Modules\Task\Models\Task;
use Modules\User\Models\User\User;

/**
 * Class PmHelperRepository
 *
 * PM Helper functions
 *
 * PHP version 7.1.3
 *
 * @category  Helper
 * @package   Modules\Helper
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class PmHelperRepository
{
    /**
     * Get data for dashboard.
     *
     * @param Request $request [Request for get dashboard data]
     *
     * @return Response
     */
    public function getDashboardData($request)
    {
        $user = Auth::user();
        $length = $request->get('length');

        $data['pending_leave'] = 0;
        $data['approved_leave'] = 0;

        if ($user->is_client) {
            $data['total_projects'] = $user->projects()->whereNotIn('status', [ ])->count();
            $data['in_progress_projects'] = $user->projects()->whereIn('status', [2])->count();
            $data['overdue_projects'] = $user->projects()
                ->whereIn('status', [1, 2, 3])
                ->whereDate('end_date', '<', Carbon::now())
                ->count();

            $projects = $user->projects()->with([
                'users' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'avatar')->where('edit', 1);
                }
            ]);

            $estimates = Estimate::where('client_id', $user->id);
            $estimatesList = Estimate::where('client_id', $user->id)->whereNotIn('status', ['draft']);
            $invoices = Invoice::where('client_id', $user->id);
            $payments = Payment::where('client_id', $user->id);

        } else {
            $data['total_projects'] = $user->projects(true)->whereNotIn('status', [ ])->count();
            $data['in_progress_projects'] = $user->projects(true)->whereIn('status', [2])->count();
            $data['overdue_projects'] = $user->projects(true)
                ->whereIn('status', [1, 2, 3])
                ->whereDate('end_date', '<', Carbon::now())
                ->count();

            $projects = $user->projects(true)->with([
                'users' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'avatar')->where('edit', 1);
                }
            ]);
            
            if (!$user->hasRole('admin') && !$user->is_super_admin) {
                $estimates = Estimate::where('user_id', $user->id);
                $estimatesList = Estimate::where('user_id', $user->id);
                $invoices = Invoice::where('user_id', $user->id);
                $payments = Payment::where('user_id', $user->id);
            }else{
                $estimates = new Estimate();
                $estimatesList = new Estimate();
                $invoices = new Invoice();
                $payments = new Payment();

                // Current month leaves count.
                $data['approved_leave'] = Leave::whereIn('status', [2, 5])
                    ->whereMonth('leave_date', Carbon::now()->month)
                    ->sum('leave_day');

                $data['pending_leave'] = Leave::where('status', 1)
                    ->whereMonth('leave_date', Carbon::now()->month)
                    ->sum('leave_day');
            }
        }

        $data['pending_tasks'] = Task::where(function ($query) use ($user) {
            $query->where('assign_to', $user->id)->orWhere('created_by', $user->id);
        })->whereNotIn('status', [5, 6])->count();
        $data['total_tasks'] = Task::where(function ($query) use ($user) {
            $query->where('assign_to', $user->id)->orWhere('created_by', $user->id);
        })->whereIn('status', [1,2,3,4,5,6])->count();
        $data['in_progress_tasks'] = Task::where(function ($query) use ($user) {
            $query->where('assign_to', $user->id)->orWhere('created_by', $user->id);
        })->whereIn('status', [2])->count();
        $data['overdue_tasks'] = Task::where(function ($query) use ($user) {
            $query->where('assign_to', $user->id)->orWhere('created_by', $user->id);
        })->whereIn('status', [1, 2, 3, 4])->whereDate('task_end_date', '<', Carbon::now())->count();

        // Deffered means rejected or not valid.
        $data['pending_defects'] = Defect::where(function ($query) use ($user) {
            $query->where('assign_member', $user->id)->orWhere('create_user_id', $user->id);
        })->whereNotIn('status', [2, 5, 7])->count();
        $data['in_progress_defects'] = Defect::where(function ($query) use ($user) {
            $query->where('assign_member', $user->id)->orWhere('create_user_id', $user->id);
        })->whereIn('status', [3])->count();
        $data['overdue_defects'] = Defect::where(function ($query) use ($user) {
            $query->where('assign_member', $user->id)->orWhere('create_user_id', $user->id);
        })->whereIn('status', [1, 3, 4, 6])->whereDate('end_date', '<', Carbon::now())->count();

        $data['pending_incidents'] = Incident::where(function ($query) use ($user) {
            $query->where('assign_to', $user->id)->orWhere('create_user_id', $user->id);
        })->whereNotIn('status', [4,5,7])->count();
        $data['in_progress_incidents'] = Incident::where(function ($query) use ($user) {
            $query->where('assign_to', $user->id)->orWhere('create_user_id', $user->id);
        })->whereIn('status', [3])->count();
        $data['overdue_incidents'] = Incident::where(function ($query) use ($user) {
            $query->where('assign_to', $user->id)->orWhere('create_user_id', $user->id);
        })->whereIn('status', [1, 2, 3, 6])->whereDate('end_date', '<', Carbon::now())->count();

        $data['accepted_estimate'] = $estimates->where('status', 'accepted')->count();
        $data['declined_estimate'] = $estimates->where('status', 'declined')->count();

        $paidInvoicesQuery = clone $invoices;
        $data['paid_invoice'] = $paidInvoicesQuery->where('status', 'paid')->count();
        $data['unpaid_invoice'] = $invoices->where('status', 'unpaid')->count();
        // Current month due and received payments count.
        $data['due_payment'] = $invoices->whereDate('due_date', '>=', Carbon::now()->startOfMonth())
                ->whereDate('due_date', '<', Carbon::now())
                ->whereIn('status', ['partially_paid', 'unpaid'])
                ->count();
        $data['received_payment'] = $payments->whereMonth('date', Carbon::now()->month)
                ->where('status', 'successful')
                ->count();

        // Project count by status.
        $data['project_count_by_status'] = $this->_getProjectCount($user);

        // Task count by status.
        if ($user->hasRole('admin') || $user->is_super_admin) {
            $data['task_count_by_status'] = Task::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get();
        }else{
            $data['task_count_by_status'] = Task::select('status', DB::raw('count(*) as total'))
                ->where('assign_to', $user->id)
                ->groupBy('status')
                ->get();
        }

        // Task, Defect, Incident, project count by month.
        $data['count_by_month']['monthly_project'] = $this->_getCountByMonths();
        $data['count_by_month']['all_invoice_client_user'] = $this->getAllInvoiceClientUser();
        $data['count_by_year']['yearly_project'] = $this->_getCountByYear();
        $data['count_by_year']['all_invoice_client_user'] = $this->getAllInvoiceClientUser();

        // Projects.
        $data['projects'] = $projects->whereNotIn('status', [4, 5])->orderBy('created_at', 'DESC')
            ->take($length)
            ->get();

        // Tasks.
        $data['tasks'] = Task::with([
            'assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            }
        ])
        ->where(function ($query) use ($user) {
            $query->where('assign_to', $user->id)->orWhere('created_by', $user->id);
        })
        ->whereNotIn('status', [5,6])
        ->orderBy('created_at', 'DESC')
        ->take($length)
        ->get();

        // Defects.
        $data['defects'] = Defect::with([
            'assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            }
        ])
        ->where(function ($query) use ($user) {
            $query->where('assign_member', $user->id)->orWhere('create_user_id', $user->id);
        })
        ->whereNotIn('status', [2,5])
        ->orderBy('created_at', 'DESC')
        ->take($length)
        ->get();

        // Incidents.
        $data['incidents'] = Incident::with([
            'assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            }
        ])
        ->where(function ($query) use ($user) {
            $query->where('assign_to', $user->id)->orWhere('create_user_id', $user->id);
        })
        ->whereNotIn('status', [4,7])
        ->orderBy('created_at', 'DESC')
        ->take($length)
        ->get();

        // Estimates
        $data['estimates'] = $estimatesList->with(['client'])
            ->orderBy('estimate_date', 'desc')
            ->take($length)
            ->get();

        // Top due invoices.
        $data['invoices'] = $invoices->with(['client'])->whereNotIn('status', ['paid'])
            ->whereDate('due_date', '<',  Carbon::now())
            ->orderBy('due_date', 'desc')
            ->take($length)
            ->get();
        
        return $data;
    }

    /**
     * Get project count by status.
     *
     * @return json
     */
    public function _getProjectCount($user)
    {
        if ($user->hasRole('admin') || $user->is_super_admin) {
            $result['all'] = Project::whereIn('status', [1,2,3,4,5])->count();
            if ($result['all'] > 0) {
                $result['open'] = Project::where('status', 1)->count();
                $result['in_progress'] = Project::where('status', 2)->count();
                $result['on_hold'] = Project::where('status', 3)->count();
                $result['cancel'] = Project::where('status', 4)->count();
                $result['completed'] = Project::where('status', 5)->count();
            }
        }else{
            $result['all'] = $user->projects()->whereIn('status', [1,2,3,4,5])->count();
            if ($result['all'] > 0) {
                $result['open'] = $user->projects()->where('status', 1)->count();
                $result['in_progress'] = $user->projects()->where('status', 2)->count();
                $result['on_hold'] = $user->projects()->where('status', 3)->count();
                $result['cancel'] = $user->projects()->where('status', 4)->count();
                $result['completed'] = $user->projects()->where('status', 5)->count();
            }
        }
        return $result;
    }

    /**
     * Get task, defect, incident count by month for dashboard chart.
     *
     * @return Response
     */
    public function _getCountByMonths()
    {
        $user = Auth::user();
        $result = [];
        for ($i=1; $i < 13; $i++) {
            $month = date('n', mktime(0, 0, 0, $i, 1));
            $result[$month] = [
                "tasks" => 0,
                // "defects" => 0,
                // "incidents" => 0,
                "projects" => 0, // Add project count for each month
                "project_bill" => 0, 
                "project_id" => [], 
            ];
        }

        $tasks = Task::select(
            DB::raw('count(id) as `count`'),
            DB::raw('YEAR(task_start_date) year'),
            DB::raw('MONTH(task_start_date) month')
        );

        // $defects = Defect::select(
        //     DB::raw('count(id) as `count`'),
        //     DB::raw('YEAR(start_date) year'),
        //     DB::raw('MONTH(start_date) month')
        // );

        // $incidents = Incident::select(
        //     DB::raw('count(id) as `count`'),
        //     DB::raw('YEAR(start_date) year'),
        //     DB::raw('MONTH(start_date) month')
        // );

        $projects = Project::select(
            DB::raw('count(id) as `count`'),
            DB::raw('YEAR(start_date) year'),
            DB::raw('MONTH(start_date) month'),
            DB::raw('GROUP_CONCAT(id) as project_id'),
        );

        $invoices = Invoice::select(
            DB::raw('SUM(total_amount) as total_bill'),
            DB::raw('YEAR(created_at) year'),
            DB::raw('MONTH(created_at) month')
        );
    

        if ($user->hasRole('admin') || $user->is_super_admin) {
        } else {
            $tasks->where('assign_to', $user->id);
            // $defects->where('assign_member', $user->id);
            // $incidents->where('assign_to', $user->id);
            $projects->where('user_id', $user->id); // Adjust this based on your project user assignment logic
            $invoices->whereHas('project', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Tasks
        $tasks = $tasks->whereYear('task_start_date', date('Y'))
            ->groupBy(DB::raw('YEAR(task_start_date)'), DB::raw('MONTH(task_start_date)'))
            ->get();
        foreach ($tasks as $key => $value) {
            $result[$value->month]['tasks'] = $value->count;
        }

        // // Defects
        // $defects = $defects->whereYear('start_date', date('Y'))
        //     ->groupBy(DB::raw('YEAR(start_date)'), DB::raw('MONTH(start_date)'))
        //     ->get();
        // foreach ($defects as $key => $value) {
        //     $result[$value->month]['defects'] = $value->count;
        // }

        // // Incidents
        // $incidents = $incidents->whereYear('start_date', date('Y'))
        //     ->groupBy(DB::raw('YEAR(start_date)'), DB::raw('MONTH(start_date)'))
        //     ->get();
        // foreach ($incidents as $key => $value) {
        //     $result[$value->month]['incidents'] = $value->count;
        // }

        // Projects
        $projects = $projects->whereYear('start_date', date('Y'))
            ->groupBy(DB::raw('YEAR(start_date)'), DB::raw('MONTH(start_date)'))
            ->get();
        foreach ($projects as $key => $value) {
            $result[$value->month]['projects'] = $value->count;
            $result[$value->month]['project_id'] = explode(',', $value->project_id);
        }

         // Project Bill
        $invoices = $invoices->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
        ->get();
        foreach ($invoices as $key => $value) {
            $result[$value->month]['project_bill'] = $value->total_bill;
        }


        return $result;
    }


    public function _getCountByYear()
    {
        $user = Auth::user();
        $result = [];

        // Yearly report initialization
        $yearlyProjects = [];
        for ($i = date('Y'); $i >= date('Y') - 4; $i--) {
            $yearlyProjects[$i] = [
                "projects" => 0,
                "project_bill" => 0,
                "project_id" => [], // Add the new field
                "project_status" => [],
                "project_user" => [],
                "project_client" => [],

            ];
        }

        // Yearly project report processing
        $projects = Project::select(
            DB::raw('count(id) as `count`'),
            DB::raw('GROUP_CONCAT(id) as project_id'), // Concatenate project IDs
            DB::raw('YEAR(start_date) year'),
            DB::raw('GROUP_CONCAT(status) as project_status'),
            DB::raw('GROUP_CONCAT(SUBSTRING_INDEX(assign_members, ",", 1)) as project_user'),
            DB::raw('GROUP_CONCAT(COALESCE(client_id, "Unassign")) as project_client')
        );

        $invoices = Invoice::select(
            DB::raw('SUM(total_amount) as total_bill'),
            DB::raw('YEAR(created_at) year')
        );

        if ($user->hasRole('admin') || $user->is_super_admin) {
            // No additional conditions for admin
        } else {
            // Add conditions for non-admin users
            $projects->where('user_id', $user->id);
            $invoices->whereHas('project', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Yearly projects
        $yearlyProjectsData = $projects->whereBetween('start_date', [
            now()->subYears(9)->startOfYear(),
            now()->endOfYear()
        ])->groupBy(
            DB::raw('YEAR(start_date)')
        )->get();

        foreach ($yearlyProjectsData as $key => $value) {
            $yearlyProjects[$value->year]['projects'] = $value->count;
            $yearlyProjects[$value->year]['project_id'] = explode(',', $value->project_id);
            $yearlyProjects[$value->year]['project_status'] = explode(',', $value->project_status);
            $yearlyProjects[$value->year]['project_user'] = explode(',', $value->project_user);
            $yearlyProjects[$value->year]['project_client'] = explode(',', $value->project_client);

        }

        // Yearly project bill
        $yearlyInvoicesData = $invoices->whereBetween('created_at', [
            now()->subYears(9)->startOfYear(),
            now()->endOfYear()
        ])->groupBy(
            DB::raw('YEAR(created_at)')
        )->get();

        foreach ($yearlyInvoicesData as $key => $value) {
            $yearlyProjects[$value->year]['project_bill'] = $value->total_bill;
        }

        // Combine monthly and yearly reports
        // $result['yearly'] = $yearlyProjects;
 
        // return $result;
        return $yearlyProjects;
    }


    public function getAllInvoiceClientUser()
    {
        $user = Auth::user();

        $invoices = Invoice::select(
            'project_id',
            'client_id',
            'total_amount',
            'total_due_amount', // Add any other fields you need
        );

        if (!($user->hasRole('admin') || $user->is_super_admin)) {
            // Add conditions for non-admin users
            $invoices->whereHas('project', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $all_clients = [];

        // Add condition to retrieve all users if admin or super admin
        if ($user->hasRole('admin') || $user->is_super_admin) {
            // You may customize this condition based on your user structure
            $all_clients = User::select(
                'id',
                'username',
                'email', // Add any other fields you need
            )->where('is_client', 1) // Add condition for is_client = 1

                ->get();
        }

        $all_users = [];

        // Add condition to retrieve all users if admin or super admin
        if ($user->hasRole('admin') || $user->is_super_admin) {
            // You may customize this condition based on your user structure
            $all_users = User::select(
                'id',
                'username',
                'email', // Add any other fields you need
            )
                ->get();
        }


        $result = [
            'all_invoices' => $invoices->get(),
            'all_clients' => $all_clients,
            'all_users' => $all_users,
        ];
        return $result;
    }

}
