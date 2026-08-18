@props(['paginator' => null])

@if($paginator && $paginator->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $paginator->links() }}
    </div>
@endif
