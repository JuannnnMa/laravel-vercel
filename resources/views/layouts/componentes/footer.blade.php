<!-- Global Modal Container -->
<div id="globalModal" class="modal-custom">
    <div class="modal-custom-content">
        <div class="modal-custom-header">
            <h3 class="modal-custom-title" id="globalModalTitle">Título</h3>
            <button type="button" class="modal-custom-close" onclick="closeGlobalModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-custom-body" id="globalModalBody">
            <!-- Content loaded via Ajax -->
        </div>
    </div>
</div>

<!-- Scripts -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    $(document).ready(function() {
        $.extend( true, $.fn.dataTable.defaults, {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            },
            responsive: true,
            autoWidth: false
        });
        
        $('table.display:not(#kardexTable):not(#kardexTableDocente), table.table-striped:not(#kardexTable):not(#kardexTableDocente)').DataTable();
    });


    function openModal(url, title) {
        $('#globalModalTitle').text(title);
        $('#globalModalBody').html('<div style="text-align:center; padding: 40px; color: #6b7280;"><i class="fas fa-circle-notch fa-spin fa-2x"></i><p style="margin-top:10px;">Cargando contenido...</p></div>');
        $('#globalModal').addClass('active');
        $('body').css('overflow', 'hidden');

        $.get(url, function(data) {
            $('#globalModalBody').html(data);
            
            $('#globalModalBody .select2').select2({
                dropdownParent: $('#globalModal'),
                width: '100%',
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    }
                }
            });
        }).fail(function() {
            $('#globalModalBody').html('<div class="alert alert-danger">Error al cargar el contenido. Por favor intente nuevamente.</div>');
        });
    }

    function closeGlobalModal() {
        $('#globalModal').removeClass('active');
        setTimeout(() => {
            $('#globalModalBody').html('');
        }, 300); 
        
        $('body').css('overflow', 'auto');
    }

    
    $(document).on('click', function(event) {
        if ($(event.target).hasClass('modal-custom')) {
            closeGlobalModal();
        }
    });
    
    
    $(document).on('keydown', function(event) {
        if (event.key === "Escape") {
            closeGlobalModal();
        }
    });
    
 
    @if(session('success'))
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    @endif


    function confirmDelete(formId) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Sí, eliminarlo!"
        }).then((result) => {
            if (result.isConfirmed) {
  
                document.getElementById(formId).submit();
  
            }
        });
    }
</script>

@stack('scripts')

@yield('scripts')
