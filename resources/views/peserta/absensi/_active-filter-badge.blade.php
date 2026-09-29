{{--
    Active filter chip with a remove (x) button.

    Props:
      - $icon      (string) : Font Awesome class, e.g. "fas fa-search"
      - $removeUrl (string) : URL that clears this filter
      - $title     (string) : tooltip for the remove link

    Slot: the visible filter label (rendered escaped).
--}}
@props(['icon', 'removeUrl', 'title'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60']) }}>
    <i class="{{ $icon }} text-[10px]"></i> {{ $slot }}
    <a href="{{ $removeUrl }}" class="hover:text-rose-500 transition ml-1" title="{{ $title }}"><i class="fas fa-times"></i></a>
</span>
