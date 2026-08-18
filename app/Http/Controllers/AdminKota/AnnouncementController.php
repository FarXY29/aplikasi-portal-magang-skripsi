<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Jobs\SendAnnouncementBroadcastJob;
use App\Models\Announcement;
use App\Models\BroadcastLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with(['creator', 'broadcastLogs'])->recent();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('target_audience')) {
            $query->where('target_audience', $request->target_audience);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->published();
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $announcements = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => Announcement::count(),
            'active' => Announcement::published()->count(),
            'broadcasted' => Announcement::where('send_email_broadcast', true)->count(),
            'urgent' => Announcement::where('type', 'urgent')->count(),
        ];

        return view('admin_kota.announcements.index', compact('announcements', 'stats'));
    }

    public function create()
    {
        return view('admin_kota.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,urgent,event',
            'target_audience' => 'required|in:all,peserta,admin_instansi,pembimbing',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'send_email_broadcast' => 'nullable|boolean',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner_image')) {
            $bannerPath = $request->file('banner_image')->store('announcements', 'public');
        }

        $isPublished = $request->boolean('is_published', true);
        $sendBroadcast = $request->boolean('send_email_broadcast', false);

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'target_audience' => $validated['target_audience'],
            'banner_image' => $bannerPath,
            'is_published' => $isPublished,
            'published_at' => $validated['published_at'] ?? ($isPublished ? now() : null),
            'expires_at' => $validated['expires_at'] ?? null,
            'send_email_broadcast' => $sendBroadcast,
            'created_by' => Auth::id() ?? 1,
        ]);

        if ($sendBroadcast) {
            $log = BroadcastLog::create([
                'announcement_id' => $announcement->id,
                'recipient_role' => $announcement->target_audience,
                'status' => 'processing',
            ]);

            SendAnnouncementBroadcastJob::dispatchAfterResponse($announcement, $log);
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman baru berhasil diterbitkan' . ($sendBroadcast ? ' dan notifikasi broadcast email telah dikirimkan!' : '.'));
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin_kota.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,urgent,event',
            'target_audience' => 'required|in:all,peserta,admin_instansi,pembimbing',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($announcement->banner_image && Storage::disk('public')->exists($announcement->banner_image)) {
                Storage::disk('public')->delete($announcement->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('announcements', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published');
        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->banner_image && Storage::disk('public')->exists($announcement->banner_image)) {
            Storage::disk('public')->delete($announcement->banner_image);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function togglePublish($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_published = ! $announcement->is_published;
        if ($announcement->is_published && empty($announcement->published_at)) {
            $announcement->published_at = now();
        }
        $announcement->save();

        $statusText = $announcement->is_published ? 'ditayangkan' : 'disembunyikan (draft)';

        return redirect()->back()
            ->with('success', "Status pengumuman berhasil diubah menjadi {$statusText}.");
    }

    public function sendBroadcast($id)
    {
        $announcement = Announcement::findOrFail($id);

        $log = BroadcastLog::create([
            'announcement_id' => $announcement->id,
            'recipient_role' => $announcement->target_audience,
            'status' => 'processing',
        ]);

        SendAnnouncementBroadcastJob::dispatchAfterResponse($announcement, $log);

        return redirect()->back()
            ->with('success', "Proses pengiriman broadcast email pengumuman telah dijalankan dan berhasil disiarkan.");
    }
}
