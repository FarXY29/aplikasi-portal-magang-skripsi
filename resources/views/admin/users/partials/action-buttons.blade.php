<div class="flex items-center gap-2">
    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 transition shadow-sm">
        <i class="fas fa-edit"></i>
    </a>

    @if(auth()->id() != $user->id)
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="p-2 bg-white border border-gray-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-300 transition shadow-sm">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    @endif
</div>