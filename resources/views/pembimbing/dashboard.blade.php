<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header 
            title="Dashboard Pembimbing Akademik"
            subtitle="Monitoring aktivitas mahasiswa, verifikasi logbook harian, dan pantauan absensi magang."
            icon="fas fa-chalkboard-teacher"
        />
    </x-slot>

    <div class="py-2 bg-transparent min-h-screen font-sans">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Welcome Card --}}
            @include('pembimbing.partials._welcome-card')

            {{-- Main List Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-users text-teal-600 dark:text-teal-400"></i> Daftar Mahasiswa Bimbingan
                    </h3>
                    
                    {{-- Filter Status Segmented Control --}}
                    <form action="{{ route('pembimbing.dashboard') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
                        <div class="bg-gray-100 dark:bg-gray-900 p-1 rounded-xl flex items-center w-full justify-between md:w-auto border border-gray-200 dark:border-gray-700">
                            <label class="cursor-pointer flex-1 md:flex-initial text-center">
                                <input type="radio" name="status" value="aktif" {{ $statusFilter === 'aktif' ? 'checked' : '' }} class="sr-only peer" @change="$el.form.submit()">
                                <span class="px-3.5 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                    Sedang Aktif
                                </span>
                            </label>
                            <label class="cursor-pointer flex-1 md:flex-initial text-center">
                                <input type="radio" name="status" value="selesai" {{ $statusFilter === 'selesai' ? 'checked' : '' }} class="sr-only peer" @change="$el.form.submit()">
                                <span class="px-3.5 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                    Selesai
                                </span>
                            </label>
                            <label class="cursor-pointer flex-1 md:flex-initial text-center">
                                <input type="radio" name="status" value="semua" {{ $statusFilter === 'semua' ? 'checked' : '' }} class="sr-only peer" @change="$el.form.submit()">
                                <span class="px-3.5 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                    Semua
                                </span>
                            </label>
                        </div>
                    </form>
                </div>
                
                <div>
                    @if($applications->isEmpty())
                        <div class="p-12 text-center text-gray-400 dark:text-gray-500">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-200 dark:border-gray-700">
                                <i class="fas fa-user-graduate text-3xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Tidak Ada Mahasiswa</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tidak ada mahasiswa magang dengan status "{{ ucfirst($statusFilter) }}".</p>
                        </div>
                    @else
                        @include('pembimbing.partials._student-table', ['applications' => $applications])
                        @include('pembimbing.partials._student-cards', ['applications' => $applications])
                    @endif

                    <x-pembimbing.pagination :paginator="$applications" />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
