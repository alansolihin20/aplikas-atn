{{-- Components/modal.blade.php --}}

<!-- components/modal.blade.php -->
<div class="modal fade" id="{{ $id ?? 'modal' }}" tabindex="-1" aria-labelledby="{{ $label ?? 'modalLabel' }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $label ?? 'modalLabel' }}">{{ $title ?? 'Modal Title' }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                {{ $footer ?? '' }}
            </div>
        </div>
    </div>
</div>
