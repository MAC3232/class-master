<div id="asignaturas-list">
    @foreach ($asignaturas as $asignatura)
        <div class="checklist-item flex items-center space-x-2">
            <input type="checkbox" name="assignments[]" value="{{ $asignatura->id }}"
                   {{ in_array($asignatura->id, old('assignments', [])) ? 'checked' : '' }}
                   class="form-checkbox text-blue-500 h-5 w-5 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            <label class="text-gray-700 text-lg font-medium cursor-pointer">
                {{ $asignatura->nombre }} ({{ $asignatura->codigo }})
            </label>
        </div>
    @endforeach
</div>
