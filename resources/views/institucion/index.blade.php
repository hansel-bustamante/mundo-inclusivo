```html
@extends('layouts.main')

@section('title', 'Catálogo de Instituciones')

@section('content')
    
    @if (session('success'))
        <div class="flash-message flash-success">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    
    <div class="content-card">

        <div class="header-container">
            <div>
                <h3 class="section-title">Catálogo de Instituciones</h3>
                <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                    Gestión de instituciones educativas y organizaciones asociadas
                </p>
            </div>
            <a href="{{ route('institucion.create') }}" 
                class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Nueva Institución
            </a>
        </div>

        <div class="table-wrapper">
            
            @if ($instituciones->isEmpty())
                <div class="empty-state">
                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <p class="empty-text" style="margin-top: 1rem; font-size: 1.125rem; color: var(--color-text-medium);">
                        No hay instituciones registradas en el sistema.
                        <br>
                        <a href="{{ route('institucion.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                            Registrar Primera Institución
                        </a>
                    </p>
                </div>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th> 
                            <th>Nombre de la Institución</th>
                            <th>Tipo</th>
                            <th>Municipio</th>
                            <th class="actions-cell">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($instituciones as $institucion)
                            <tr>
                                <td style="font-weight: 600; color: var(--color-primary-dark);">
                                    {{ $loop->iteration }}
                                </td> 
                                <td style="font-weight: 600; color: var(--color-text-dark);">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                                            {{ substr($institucion->nombre_institucion, 0, 1) }}
                                        </div>
                                        {{ $institucion->nombre_institucion }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background: var(--color-bg-light); color: var(--color-text-medium); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; border: 1px solid var(--color-border);">
                                        {{ $institucion->tipo }}
                                    </span>
                                </td>
                                <td>{{ $institucion->municipio }}</td>
                                <td class="actions-cell">
                                    <div class="action-buttons">
                                        <a href="{{ route('institucion.edit', $institucion->id_institucion) }}" 
                                            class="action-link link-edit"
                                            title="Editar Institución">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        <button type="button" 
                                                onclick="openDeleteModal('{{ route('institucion.destroy', $institucion->id_institucion) }}', '{{ addslashes($institucion->nombre_institucion) }}')"
                                                class="action-link link-delete"
                                                title="Eliminar Institución">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if(method_exists($instituciones, 'links'))
                    @if($instituciones->hasPages())
                    <div style="margin-top: 2rem; display: flex; justify-content: center;">
                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                            {{ $instituciones->links() }}
                        </div>
                    </div>
                    @endif
                @endif
            @endif
        </div>

    </div>

    <div id="deleteModal" class="modal-backdrop">
        <div class="modal-content">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="width: 4rem; height: 4rem; background: linear-gradient(135deg, var(--color-danger) 0%, var(--color-danger-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h4 class="modal-title" style="color: var(--color-danger); margin-bottom: 0.5rem;">
                    Confirmar Eliminación
                </h4>
            </div>
            
            <p class="modal-text" style="text-align: center; color: var(--color-text-medium); line-height: 1.6; margin-bottom: 2rem;">
                ¿Está seguro de que desea eliminar la institución 
                <strong style="color: var(--color-text-dark);" id="institucionName"></strong>?
                <br>
                <small style="color: var(--color-text-light); font-size: 0.875rem;">
                    Esta acción no se puede deshacer y eliminará todos los registros asociados.
                </small>
            </p>
            
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="closeDeleteModal()" style="flex: 1;">Cancelar</button>
                <form id="deleteForm" method="POST" action="" style="flex: 1;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-confirm" style="width: 100%;">Sí, Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }
        }
    </style>
    
    <script>
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const institucionName = document.getElementById('institucionName');

        function openDeleteModal(actionUrl, nombreInstitucion) {
            deleteForm.action = actionUrl;
            institucionName.textContent = nombreInstitucion;
            deleteModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            deleteModal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
        
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && deleteModal.classList.contains('show')) {
                closeDeleteModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('.data-table tbody tr');
            
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-1px)';
                    this.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.05)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });
        });
    </script>

@endsection
```