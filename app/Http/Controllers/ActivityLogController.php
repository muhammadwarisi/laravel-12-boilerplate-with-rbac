<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua log, urutkan terbaru
        $query = Activity::with('causer')->orderBy('created_at', 'desc');

        // Filter berdasarkan event (created, updated, deleted)
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter berdasarkan nama pengguna (causer)
        if ($request->filled('user')) {
            $query->whereHas('causer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        // Filter berdasarkan subject_type (model)
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Ambil daftar event unik untuk dropdown filter
        $events = Activity::distinct()->pluck('event')->filter()->values();
        // Ambil daftar subject_type unik
        $subjectTypes = Activity::distinct()->pluck('subject_type')->filter()->values();

        return view('dashboard.pages.activity-logs.index', compact('logs', 'events', 'subjectTypes'));
    }
}
