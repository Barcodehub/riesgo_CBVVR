@extends('cliente.dashboard')

@section('content')


  <style>
        .fingerprint-icon {
            font-size: 1.2rem;
            margin-right: 5px;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .progress-container {
            height: 25px;
            margin: 15px 0;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .last-update {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-fingerprint me-2"></i>Gestión de Huellas Digitales
                </h5>
                <button id="registerFingerprintBtn" class="btn btn-light">
                    <i class="fas fa-plus-circle me-1"></i> Registrar Huella
                </button>
            </div>
            
            <div class="card-body">
                <!-- Alertas -->
                <div id="alertContainer" class="mb-3"></div>
                
                <!-- Tabla de huellas -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="fingerprintsTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($huellas as $huella)
                            <tr>
                                <td>{{ $huella->id }}</td>
                                <td>
                                    @if($huella->user)
                                        <strong>{{ $huella->user->nombre }} {{ $huella->user->apellido }}</strong>
                                        <div class="text-muted small">{{ $huella->user->email }}</div>
                                    @else
                                        <span class="text-danger">Usuario no encontrado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($huella->huella)
                                        <span class="badge bg-success status-badge">
                                            <i class="fas fa-check-circle"></i> Registrada
                                        </span>
                                        <div class="last-update mt-1">
                                            Actualizada: {{ $huella->updated_at->format('d/m/Y H:i') }}
                                        </div>
                                    @else
                                        <span class="badge bg-warning text-dark status-badge">
                                            <i class="fas fa-exclamation-circle"></i> No registrada
                                        </span>
                                    @endif
                                </td>
                                <td class="action-buttons">
                                    @if($huella->huella)
                                    <button class="btn btn-sm btn-outline-danger delete-fingerprint" data-id="{{ $huella->id }}">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Registro -->
    <div class="modal fade" id="fingerprintModal" tabindex="-1" aria-labelledby="fingerprintModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="fingerprintModalLabel">
                        <i class="fas fa-fingerprint me-2"></i>Registro de Huella
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-fingerprint text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center">Por favor, coloca tu dedo en el lector biométrico cuando se te indique.</p>
                    <p class="text-center small text-muted">(Serán necesarias 4 lecturas para completar el registro)</p>
                    
                    <div class="progress-container mt-4">
                        <div class="progress">
                            <div id="registrationProgress" class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="progressText" class="text-center small mt-1">0% completado</div>
                    </div>
                    
                    <div id="instructions" class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>Esperando inicio del registro...
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirmar acción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage">¿Estás seguro de que deseas eliminar esta huella digital?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmAction">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<script>
$(document).ready(function() {
    const fingerprintModal = new bootstrap.Modal(document.getElementById('fingerprintModal'));
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    let currentFingerprintId = null;
    let captureInterval;
    
    // Mostrar modal de registro
    $('#registerFingerprintBtn').click(function() {
        resetRegistrationUI();
        fingerprintModal.show();
        startRealRegistration();
    });
    
    function resetRegistrationUI() {
        $('#registrationProgress').css('width', '0%').attr('aria-valuenow', 0);
        $('#progressText').text('0% completado');
        $('#instructions').html('<i class="fas fa-info-circle me-2"></i>Inicializando lector...');
    }
    
 function startRealRegistration() {
    const modal = $('#fingerprintModal');
    const progressBar = $('#registrationProgress');
    const progressText = $('#progressText');
    const instructions = $('#instructions');
    
    // Resetear UI
    resetRegistrationUI();
    
    // Mostrar estado inicial
    updateProgress(0, 'Conectando con el lector biométrico...');
    
    // Deshabilitar botones
    modal.find('button').prop('disabled', true);
    
    // Mostrar spinner
    instructions.html('<i class="fas fa-spinner fa-spin me-2"></i>Conectando...');
    
    // Iniciar el proceso
    $.ajax({
        url: "{{ route('huella.create', ['id' => Auth::id()]) }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}"
        },
        beforeSend: function() {
            // Mostrar progreso inicial
            updateProgress(10, 'Preparando registro...');
        },
        xhr: function() {
            const xhr = new window.XMLHttpRequest();
            xhr.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.min(90, Math.round((e.loaded / e.total) * 100));
                    updateProgress(percent, 'Transfiriendo datos...');
                }
            });
            return xhr;
        },
        success: function(response) {
            if (response.success) {
                updateProgress(100, 'Huella registrada con éxito!');
                setTimeout(() => {
                    modal.modal('hide');
                    showAlert('success', 'Huella registrada correctamente');
                    setTimeout(() => location.reload(), 1500);
                }, 1000);
            } else {
                showAlert('danger', response.error || 'Error al registrar huella');
                modal.modal('hide');
            }
        },
        error: function(xhr) {
            let errorMsg = 'Error de comunicación con el servidor';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
            } else if (xhr.statusText) {
                errorMsg = xhr.statusText;
            }
            
            showAlert('danger', errorMsg);
            modal.modal('hide');
        },
        complete: function() {
            modal.find('button').prop('disabled', false);
        }
    });
}

function resetRegistrationUI() {
    $('#registrationProgress').css('width', '0%').attr('aria-valuenow', 0);
    $('#progressText').text('0% completado');
    $('#instructions').html('<i class="fas fa-info-circle me-2"></i>Preparando registro...');
}

function updateProgress(percent, message) {
    $('#registrationProgress').css('width', percent + '%').attr('aria-valuenow', percent);
    $('#progressText').text(percent + '% completado');
    $('#instructions').html(`<i class="fas fa-info-circle me-2"></i>${message}`);
}
    
    function updateProgress(percent, message) {
        $('#registrationProgress').css('width', percent + '%').attr('aria-valuenow', percent);
        $('#progressText').text(percent + '% completado');
        
        if (message) {
            let icon = percent < 100 ? 'fa-hand-pointer' : 'fa-check-circle';
            $('#instructions').html(`<i class="fas ${icon} me-2"></i>${message}`);
        }
    }
    
    function completeRegistration() {
        $('#instructions').html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando huella...');
        
        $.ajax({
            url: "{{ route('huella.create', ['id' => Auth::id()]) }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    updateProgress(100, 'Huella registrada exitosamente!');
                    setTimeout(() => {
                        fingerprintModal.hide();
                        showAlert('success', response.message);
                        setTimeout(() => location.reload(), 1500);
                    }, 1000);
                } else {
                    showAlert('danger', response.error || 'Error al registrar huella');
                    fingerprintModal.hide();
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.error || 'Error de comunicación con el servidor';
                showAlert('danger', errorMsg);
                fingerprintModal.hide();
            }
        });
    }
    
    // Eliminar huella (mantienes tu código actual)
    $(document).on('click', '.delete-fingerprint', function() {
        currentFingerprintId = $(this).data('id');
        $('#confirmMessage').html(`
            <p>¿Estás seguro de que deseas eliminar esta huella digital?</p>
            <p class="small text-muted">El usuario tendrá que registrar su huella nuevamente para acceder con este método.</p>
        `);
        $('#confirmAction').text('Eliminar').removeClass('btn-success').addClass('btn-danger');
        confirmModal.show();
    });
    
    $('#confirmAction').click(confirmDelete);
    
    function confirmDelete() {
        if (!currentFingerprintId) return;
        
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Procesando...');
        
        $.ajax({
            url: `/huella/${currentFingerprintId}`,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                confirmModal.hide();
                showAlert('success', 'Huella eliminada correctamente');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                confirmModal.hide();
                const errorMsg = xhr.responseJSON?.error || 'Error al eliminar la huella';
                showAlert('danger', errorMsg);
            },
            complete: function() {
                $('#confirmAction').prop('disabled', false).text('Eliminar');
            }
        });
    }
    
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <strong>${type === 'success' ? 'Éxito!' : 'Error!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('#alertContainer').html(alertHtml);
        
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }
    
    // Limpiar intervalos al cerrar el modal
    $('#fingerprintModal').on('hidden.bs.modal', function() {
        if (captureInterval) {
            clearInterval(captureInterval);
        }
    });
});
</script>
@endsection