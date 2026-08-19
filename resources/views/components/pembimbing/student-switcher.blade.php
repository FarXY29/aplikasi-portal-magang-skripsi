@props(['applications' => [], 'current' => null, 'routeName' => null])

<div class="flex items-center gap-3">
    <label for="switch_student" class="text-xs font-bold text-gray-500 dark:text-gray-400">Mahasiswa:</label>
    @if(isset($applications) && $applications->count() > 1)
        <select id="switch_student" onchange="window.location.href = this.value" class="px-3.5 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-200 shadow-xs focus:ring-teal-500 focus:border-teal-500">
            @foreach($applications as $item)
                <option value="{{ route($routeName, array_merge(['id' => $item->id], request()->except('id'))) }}" {{ $item->id == $current?->id ? 'selected' : '' }}>
                    {{ $item->user->name }}
                </option>
            @endforeach
        </select>
    @else
        <span class="px-3.5 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-xs font-bold text-gray-800 dark:text-gray-200 shadow-xs">
            {{ $current?->user->name }}
        </span>
    @endif
</div>
