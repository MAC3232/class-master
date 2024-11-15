@props([
    'name' => '',
    'id' => '',
    'value' => '',
    'maxLength' => 100,
    'type' => 'input', // 'input' o 'textarea'
])

<div class="character-count-wrapper">
    @if($type === 'textarea')
        <textarea
            name="{{ $name }}"
            required
            id="{{ $id }}"
            class="form-control no-resize"
            maxlength="{{ $maxLength }}"
            placeholder="Escribe algo..."
            oninput="updateCharacterCount('{{ $id }}', {{ $maxLength }})"
        >{{ $value }}</textarea>
    @else
        <input
            type="{{$type}}"
            required
            name="{{ $name }}"
            id="{{ $id }}"
            class="form-control"
            maxlength="{{ $maxLength }}"
            placeholder="Escribe aquí..."
            value="{{ $value }}"
            oninput="updateCharacterCount('{{ $id }}', {{ $maxLength }})"
        />
    @endif
    <small id="count-{{ $id }}">0/{{ $maxLength }}</small>
</div>

<script>
    function updateCharacterCount(fieldId, maxLength) {
        var field = document.getElementById(fieldId);
        var count = document.getElementById('count-' + fieldId);
        count.textContent = field.value.length + '/' + maxLength;
    }
</script>

<style>
    .character-count-wrapper {
        position: relative;
    }
    .character-count-wrapper small {
        position: absolute;
        right: 10px;
        bottom: 5px;
        font-size: 0.85rem;
        color: #6c757d;
    }
    .character-count-wrapper .no-resize {
        resize: none;
    }
</style>
