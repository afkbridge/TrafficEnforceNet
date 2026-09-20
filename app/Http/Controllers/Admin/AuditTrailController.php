<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        // ==========================================================
        // Filters
        // ==========================================================

        $search = $request->get('search');
        $action = $request->get('action');
        $userId = $request->get('user_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // ==========================================================
        // Audit Log Query
        // ==========================================================

        $query = AuditLog::with('user');

        // ==========================================================
        // Search
        // ==========================================================

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // ==========================================================
        // Filter by Action
        // ==========================================================

        if (!empty($action)) {
            $query->where('action', $action);
        }

        // ==========================================================
        // Filter by User
        // ==========================================================

        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }

        // ==========================================================
        // Filter by Date Range
        // ==========================================================

        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // ==========================================================
        // Get Audit Logs
        // ==========================================================

        $auditLogs = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // ==========================================================
        // Users for Filter
        // ==========================================================

        $users = User::orderBy('name')->get();

        // ==========================================================
        // Actions for Filter
        // ==========================================================

        $actions = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // ==========================================================
        // Return Audit Monitoring Page
        // ==========================================================

        return view('admin.audit.index', compact(
            'auditLogs',
            'users',
            'actions',
            'search',
            'action',
            'userId',
            'dateFrom',
            'dateTo'
        ));
    }
}