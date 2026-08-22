@props([
    'title',
    'breadcrumbs' => [],
])

<div class="mb-6 md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        @if(count($breadcrumbs) > 0)
            <nav class="flex text-sm text-slate-500 dark:text-gray-400 mb-2 font-medium" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        <li>
                            <div class="flex items-center">
                                @if(isset($breadcrumb['url']))
                                    <a href="{{ $breadcrumb['url'] }}" class="hover:text-teal-700 dark:hover:text-teal-400 transition-colors">
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                @else
                                    <span class="text-slate-800 dark:text-gray-100 font-semibold">{{ $breadcrumb['label'] }}</span>
                                @endif
                                
                                @if($index < count($breadcrumbs) - 1)
                                    <i class="fas fa-chevron-right text-[10px] mx-2 text-slate-300 dark:text-gray-600"></i>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
        <h2 class="text-xl font-extrabold leading-7 text-slate-900 dark:text-gray-100 sm:truncate sm:text-2xl sm:tracking-tight lg:text-3xl tracking-tight">
            {{ $title }}
        </h2>
    </div>
    
    @if(isset($actions))
        <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
