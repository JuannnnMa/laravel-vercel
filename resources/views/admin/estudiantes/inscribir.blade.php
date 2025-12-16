<form action="{{ route('admin.estudiantes.inscribir') }}" method="POST" id="inscribirEstudianteForm">
    @csrf
    <input type="hidden" name="estudiante_id" value="{{ $estudiante->id }}">
    
    <div style="background: #eef; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <strong>Estudiante:</strong> {{ $estudiante->nombres }} {{ $estudiante->apellido_paterno }} <br>
        <strong>CI:</strong> {{ $estudiante->ci }}
    </div>

    <div class="form-group">
        <label>Curso</label>
        <select id="inscripcionCurso" class="form-control" onchange="filterParalelos(this.value)" required>
            <option value="">Seleccionar Curso</option>
            @foreach($cursos as $curso)
                <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label>Paralelo</label>
        <select name="paralelo_id" id="inscripcionParalelo" class="form-control" required disabled>
            <option value="">Primero seleccione curso</option>
        </select>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary">Inscribir</button>
    </div>
</form>

<script>
    var cursosData = @json($cursos);

    function filterParalelos(cursoId) {
        var paraleloSelect = document.getElementById('inscripcionParalelo');
        paraleloSelect.innerHTML = '<option value="">Seleccionar Paralelo</option>';
        
        if (!cursoId) {
            paraleloSelect.innerHTML = '<option value="">Primero seleccione curso</option>';
            paraleloSelect.disabled = true;
            return;
        }
        
        var curso = cursosData.find(c => c.id == cursoId);
        
        if (curso && curso.paralelos && curso.paralelos.length > 0) {
            curso.paralelos.forEach(function(paralelo) {
                var option = document.createElement('option');
                option.value = paralelo.id;
                var full = paralelo.inscritos >= paralelo.cupo_maximo;
                var text = paralelo.nombre + ' (' + paralelo.inscritos + '/' + paralelo.cupo_maximo + ')';
                if (full) {
                    text += ' - LLENO';
                    option.disabled = true;
                }
                option.text = text;
                paraleloSelect.add(option);
            });
            paraleloSelect.disabled = false;
        } else {
            paraleloSelect.innerHTML = '<option value="">No hay paralelos disponibles</option>';
            paraleloSelect.disabled = true;
        }
    }

</script>
