<div class="flex items-center gap-2">
    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 transition shadow-sm" title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    @php $sessionCount = $user->activeSessions()->count(); @endphp
    @if($sessionCount > 0)
        <form action="{{ route('admin.users.reset-sessions', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mereset semua sesi aktif ({{ $sessionCount }}) milik {{ $user->name }}?')">
            @csrf
            <button type="submit" class="p-2 bg-white border border-amber-300 rounded-lg text-amber-600 hover:bg-amber-50 hover:border-amber-400 transition shadow-sm relative" title="Reset {{ $sessionCount }} Sesi Aktif">
                <i class="fas fa-power-off"></i>
                <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-green-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $sessionCount }}</span>
            </button>
        </form>
    @endif

    @if(auth()->id() != $user->id)
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="p-2 bg-white border border-gray-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-300 transition shadow-sm" title="Hapus">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    @endif
</div>