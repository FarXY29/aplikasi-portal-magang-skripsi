<div class="flex flex-col gap-1">
    @if($user->instansi)
        <div class="flex items-center text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-950/40 px-2 py-1 rounded-md w-fit text-xs border border-teal-100 dark:border-teal-900/40">
            <i class="fas fa-building mr-1.5"></i> <span class="truncate max-w-[180px]">{{ $user->instansi->nama_dinas }}</span>
        </div>
    @elseif($user->asal_instansi)
        <div class="flex items-center text-blue-600 dark:text-blue-400 font-medium bg-blue-50 dark:bg-blue-950/40 px-2 py-1 rounded-md w-fit text-xs border border-blue-100 dark:border-blue-900/40">
            <i class="fas fa-university mr-1.5"></i> <span class="truncate max-w-[180px]">{{ $user->asal_instansi }}</span>
        </div>
    @endif

    @if($user->nik || $user->phone)
        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex flex-wrap items-center gap-2">
            @if($user->phone) <span><i class="fas fa-phone-alt mr-1 text-gray-400 dark:text-gray-500"></i> {{ $user->phone }}</span> @endif
        </div>
    @endif
</div>