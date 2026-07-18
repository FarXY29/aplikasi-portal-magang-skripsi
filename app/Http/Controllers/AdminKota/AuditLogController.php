<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Menampilkan daftar Audit Log (Visual Audit Trail)
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Filter Action
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }

        // Filter Model
        if ($request->has('model_type') && $request->model_type != '') {
            $query->where('auditable_type', $request->model_type);
        }

        // Filter Search (User name atau model ID)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })
                ->orWhere('auditable_id', 'like', "%{$search}%")
                ->orWhere('action', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->paginate(20)->withQueryString();

        // Distinct actions & models for filters
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        $models = AuditLog::select('auditable_type')->whereNotNull('auditable_type')->distinct()->pluck('auditable_type');

        return view('admin_kota.audit_trail', compact('auditLogs', 'actions', 'models'));
    }
}
