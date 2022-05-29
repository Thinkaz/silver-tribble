@if (isset($link))
    <a href="{{ $link ?? "#" }}" class="btn {{ $style ?? "btn-accent"}}" role="button"
            @if (isset($tooltip))
                data-tippy-content="{{ $tooltip }}"
            @endif
            @if (isset($modal))
                data-toggle="modal"
                data-target="{{ $modal }}"
            @endif
            @if (isset($dismiss))
                data-dismiss="{{ $dismiss }}"
            @endif
            >
        @if (isset($icon))
            <i class="{{ $icon }}"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type ?? "button" }}" class="btn {{ $style ?? "btn-accent" }}"
            @if (isset($tooltip))
                data-tippy-content="{{ $tooltip }}"
            @endif
            @if (isset($modal))
                data-toggle="modal"
                data-target="{{ $modal }}"
            @endif
            @if (isset($dismiss))
                data-dismiss="{{ $dismiss }}"
            @endif
            >
        @if (isset($icon))
            <i class="{{ $icon }}"></i>
        @endif
        {{ $slot }}
    </button>
@endif