@props([
    'title',
    'breadcrumbs' => [],
])

<div class="mb-6 md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        @if(count($breadcrumbs) > 0)
            <nav class="flex text-sm text-gray-500 mb-2 font-medium" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        <li>
                            <div class="flex items-center">
                                @if(isset($breadcrumb['url']))
                                    <a href="{{ $breadcrumb['url'] }}" class="hover:text-teal-600 transition-colors">
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                @else
                                    <span class="text-gray-900">{{ $breadcrumb['label'] }}</span>
                                @endif
                                
                                @if($index < count($breadcrumbs) - 1)
                                    <i class="fas fa-chevron-right text-[10px] mx-2 text-gray-400"></i>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            {{ $title }}
        </h2>
    </div>
    
    @if(isset($actions))
        <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
