@props(['align' => 'right', 'width' => '48', 'contentClasses' => ''])

<div class="dropdown">
    {{ $trigger }}
    <div class="dropdown-menu dropdown-menu-{{ $align === 'right' ? 'end' : 'start' }}">
        {{ $content }}
    </div>
</div>
