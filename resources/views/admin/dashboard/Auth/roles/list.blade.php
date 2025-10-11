@extends('admin.layout')

@section('title', 'Roles')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Roles</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-theme">Add New Role</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        @include('admin.components.message')
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Name</th>
                                <th>Permission</th>
                                <th>Created At</th>
                                <th width="100">Status</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->permissions->pluck('name')->join(', ') }}</td>
                                    <td>{{ $role->created_at->format('d M Y')  }}</td>
                                    <td>
                                        @if ($role->is_active)
                                            <svg class="text-success-500 h-6 w-6 icon-theme"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-primary w-4 h-4 mr-1">
                                            <svg class="filament-link-icon w-4 h-4 mr-1 icon-theme" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="" aria-hidden="true">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="#" class="text-danger w-4 h-4 mr-1 btn-delete"
                                            data-url="{{ route('admin.roles.delete', $role->id) }}"
                                            data-name="{{ $role->name }}">
                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination m-0 float-right">
                        {{-- <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li> --}}

                            {{ $roles->links() }}

                    </ul>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white py-2">
        <h6 class="modal-title">Delete Permission</h6>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="mb-0 text-dark">Are you sure you want to delete <strong id="delItemName"></strong>?</p>
      </div>
      <div class="modal-footer py-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">Yes, Delete</button>
      </div>
    </div>
  </div>
</div>


@endsection



@push('scripts')
<script>
$(function () {
  let deleteUrl = null;

  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    deleteUrl = $(this).data('url');
    const name = $(this).data('name') || 'this permission';
    $('#delItemName').text(name);
    $('#confirmDeleteModal').modal('show');
  });

  $('#confirmDeleteBtn').on('click', function () {
    if (!deleteUrl) return;

    const $btn = $(this);
    $btn.prop('disabled', true).text('Deleting…');

    $.ajax({
      url: deleteUrl,
      method: 'POST',              
      dataType: 'json',
      data: {
        _method: 'DELETE',
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      headers: { 'Accept': 'application/json' }
    })
    .done(function (res) {
      if (typeof showPopup === 'function') {
        showPopup(res.message || 'Deleted successfully.', 'success', 1200);
      }
    })
    .fail(function (xhr) {
      if (typeof showPopup === 'function') {
        showPopup('Delete failed. Reloading…', 'danger', 1500);
      }
    })
    .always(function () {
      setTimeout(function(){
        window.location.reload();
      }, 300);
    });
  });

  $('#confirmDeleteModal').on('hidden.bs.modal', function () {
    deleteUrl = null;
    $('#confirmDeleteBtn').prop('disabled', false).text('Yes, Delete');
  });
});
</script>
@endpush
