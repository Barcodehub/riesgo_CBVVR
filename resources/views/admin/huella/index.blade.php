@extends('admin.dashboard')

@section('content')


<div class="w-full border p-4 m-4">
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Gestión de Huellas Digitales</h6>
         
            <form action="{{ route('huella.create', ['id' => 1]) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary" type="submit">
                                 <i class="fas fa-fingerprint mr-2"></i> Crear Huella
                             </button>
                            
                        </form>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="huellasTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Huella Registrada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($huellas as $huella)
                        <tr>
                            <td>{{ $huella->id }}</td>
                            <td>
                    @if($huella->user)
                        {{ $huella->user->nombre }} {{ $huella->user->apellido }}
                        <small class="text-muted d-block">{{ $huella->user->email }}</small>
                    @else
                        <span class="text-danger">Usuario no encontrado (ID: {{ $huella->id_user }})</span>
                    @endif
                </td>
                            <td>
                                  @if(isset($huella->huella) && !empty($huella->huella))
        <span>Registrada</span>
        <small class="text-muted d-block">Última actualización: {{ $huella->updated_at->format('d/m/Y') }}</small>
    @else
        <span class="text-danger">No registrada</span>
        @if($huella->created_at)
            <small class="text-muted d-block">Registro vacío desde: {{ $huella->created_at->format('d/m/Y') }}</small>
        @endif
    @endif
                            </td>
                            <td>
                                    <form action="{{ route('huella.destroy', $huella->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('¿Está seguro de eliminar esta huella?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                               
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

{{-- @push('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        // DataTable
             if ($.fn.DataTable) {
            $('#huellasTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                },
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip><"clear">',
                initComplete: function() {
                    console.log('DataTable inicializado correctamente');
                }
            });
        } else {
            console.error('DataTables no está cargado correctamente');
            // Fallback: Convertir la tabla en una tabla básica ordenable
            $('#huellasTable').addClass('table table-striped table-bordered');
        }

    });
</script>
@endpush --}}