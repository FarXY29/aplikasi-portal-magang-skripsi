<x-app-layout>
    <div class="p-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Audit Trail (Keamanan)</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Riwayat aktivitas pengguna, perubahan data, dan akses sistem.</p>
            </div>
        </div>

        <!-- Filter & Pencarian -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
            <form method="GET" action="{{ route('admin.audit_trail') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Cari User/Model ID</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Filter Aksi</label>
                    <select name="action" class="w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Semua Aksi</option>
                        @foreach($actions as $act)
                            <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Filter Model</label>
                    <select name="model_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Semua Model</option>
                        @foreach($models as $mod)
                            <option value="{{ $mod }}" {{ request('model_type') == $mod ? 'selected' : '' }}>{{ class_basename($mod) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm w-full md:w-auto">
                        Terapkan
                    </button>
                    @if(request()->anyFilled(['search', 'action', 'model_type']))
                        <a href="{{ route('admin.audit_trail') }}" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-medium transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Waktu & IP</th>
                            <th class="px-6 py-4 font-semibold">Aktor (User)</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                            <th class="px-6 py-4 font-semibold">Model & ID</th>
                            <th class="px-6 py-4 font-semibold text-right">Detail Data (Diff)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($auditLogs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $log->ip_address }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->user)
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $log->user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user->email }} ({{ ucfirst(str_replace('_', ' ', $log->user->role)) }})</div>
                                @else
                                    <span class="text-gray-400 italic">System / Unauthenticated</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ Str::contains($log->action, 'create') ? 'bg-green-100 text-green-700' : (Str::contains($log->action, 'update') 'bg-blue-100 text-blue-700' 'delete') 'bg-red-100 text-red-700' 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300')) }}">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900 dark:text-gray-100 font-medium">{{ $log->auditable_type ? class_basename($log->auditable_type) : '-' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $log->auditable_id ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button type="button" 
                                    @click="openMetadataModal('{{ addslashes(json_encode($log->metadata)) }}')" 
                                    class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-history text-3xl text-gray-300 mb-3 block"></i>
                                Belum ada log aktivitas yang terekam.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($auditLogs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $auditLogs->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Metadata Modal menggunakan Alpine.js -->
    <div x-data="{ 
            modalOpen: false, 
            metadata: {} 
        }" 
        @open-metadata-modal.window="
            metadata = JSON.parse($event.detail.metadata || '{}'); 
            modalOpen = true;
        ">
        
        <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="modalOpen" 
                    x-transition:enter="ease-out duration-300" 
                    x-transition:enter-start="opacity-0" 
                    x-transition:enter-end="opacity-100" 
                    x-transition:leave="ease-in duration-200" 
                    x-transition:leave-start="opacity-100" 
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="modalOpen" 
                    x-transition:enter="ease-out duration-300" 
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave="ease-in duration-200" 
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-info-circle text-indigo-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Detail Metadata
                                </h3>
                                <div class="mt-4 border rounded-lg p-3 bg-gray-50 dark:bg-gray-900 text-left">
                                    <pre class="text-[11px] overflow-auto max-h-96 text-gray-800 dark:text-gray-200" x-text="JSON.stringify(metadata, null, 2)"></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="modalOpen = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine Function call to trigger event -->
    <script>
        function openMetadataModal(metadataStr) {
            window.dispatchEvent(new CustomEvent('open-metadata-modal', {
                detail: {
                    metadata: metadataStr
                }
            }));
        }
    </script>
</x-app-layout>
