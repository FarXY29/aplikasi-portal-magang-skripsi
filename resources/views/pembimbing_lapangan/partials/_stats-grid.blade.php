@props(['interns', 'pendingLogbooks' => 0, 'pendingAttendance' => 0])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Total Bimbingan Card --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xs border border-indigo-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-indigo-500 dark:text-indigo-400 uppercase tracking-widest mb-1">Total Bimbingan</p>
                <h3 class="text-3xl font-black text-gray-800 dark:text-gray-100">{{ $interns->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-xs border border-indigo-100 dark:border-indigo-900/40">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-2 text-[11px] text-gray-500 dark:text-gray-400">
            <span class="font-medium">Semua mahasiswa yang ditugaskan</span>
        </div>
    </div>

    {{-- Sedang Magang Card --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xs border border-teal-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-teal-500 dark:text-teal-400 uppercase tracking-widest mb-1">Sedang Magang</p>
                <h3 class="text-3xl font-black text-gray-800 dark:text-gray-100">{{ $interns->filter(fn($i) => $i->status_value === 'diterima')->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/40 flex items-center justify-center text-teal-600 dark:text-teal-400 shadow-xs border border-teal-100 dark:border-teal-900/40">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-2 text-[11px] text-gray-500 dark:text-gray-400">
            <span class="font-medium">Peserta magang status aktif</span>
        </div>
    </div>

    {{-- Selesai / Lulus Card --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xs border border-blue-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-1">Selesai / Lulus</p>
                <h3 class="text-3xl font-black text-gray-800 dark:text-gray-100">{{ $interns->filter(fn($i) => $i->status_value === 'selesai')->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-xs border border-blue-100 dark:border-blue-900/40">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-2 text-[11px] text-gray-500 dark:text-gray-400">
            <span class="font-medium">Peserta telah menyelesaikan program</span>
        </div>
    </div>
</div>
