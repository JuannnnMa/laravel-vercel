<div class="modal-body p-4">
    <div class="d-flex align-items-center mb-3">
        <span class="badge badge-{{ $comunicado->tipo == 'urgente' ? 'danger' : ($comunicado->tipo == 'reunion' ? 'warning' : 'primary') }} px-3 py-2">
            {{ ucfirst($comunicado->tipo) }}
        </span>
        <span class="text-muted ml-auto small">
            <i class="far fa-clock"></i> {{ $comunicado->created_at->format('d/m/Y H:i') }}
        </span>
    </div>

    <h4 class="font-weight-bold text-dark mb-3">{{ $comunicado->titulo }}</h4>
    
    <div class="text-justify text-dark mb-4" style="line-height: 1.6; white-space: pre-wrap;">{{ $comunicado->mensaje }}</div>

    @if($comunicado->fecha_evento)
    <div class="alert alert-light border shadow-sm">
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <i class="fas fa-calendar-check fa-2x text-primary"></i>
            </div>
            <div>
                <h6 class="font-weight-bold mb-0">Fecha del Evento</h6>
                <div class="text-primary font-weight-bold">{{ $comunicado->fecha_evento->format('d/m/Y') }} a las {{ $comunicado->fecha_evento->format('H:i') }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="text-right mt-4 pt-3 border-top">
        <span class="text-muted small">Publicado por: <strong>{{ $comunicado->creador->name ?? 'Administración' }}</strong></span>
    </div>
</div>
<div class="modal-footer border-top-0 pt-0">
    <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cerrar</button>
</div>
