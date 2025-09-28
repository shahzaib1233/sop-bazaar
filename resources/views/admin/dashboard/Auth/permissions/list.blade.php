@extends('admin.layout')

@section('title', 'Permissions')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-11">

      <div class="card border-0 shadow-sm">
        <div class="card-body">

          <h5 class="mb-4 fw-semibold">Permissions</h5>

          <div class="table-responsive">
            <table id="permissionsTable" class="table table-hover align-middle w-100">
              <thead class="bg-light">
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Guard</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                @foreach($permissions as $p)
                  <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->guard_name }}</td>
                    <td>{{ $p->created_at }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('styles')
  {{-- DataTables + Bootstrap 5 CSS --}}
  <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  {{-- Google Fonts + Material Icons for a softer UI --}}
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
    }
    table.dataTable thead th {
      border-bottom: 2px solid #e9ecef;
      font-weight: 500;
    }
    table.dataTable tbody td {
      border-color: #f1f3f5;
    }
    .dataTables_wrapper .dataTables_filter input {
      border-radius: 8px;
      border: 1px solid #dee2e6;
      padding: 6px 12px;
      margin-left: .5em;
    }
    .dataTables_wrapper .dataTables_length select {
      border-radius: 8px;
      border: 1px solid #dee2e6;
      padding: 4px 10px;
    }
  </style>
@endpush

@push('scripts')
  {{-- jQuery + DataTables --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(function () {
      $('#permissionsTable').DataTable({
        dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6'f>>" +
             "tr" +
             "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
        paging: true,
        ordering: true,
        info: true,
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
          search: "",
          searchPlaceholder: "Search permissions...",
          lengthMenu: "Rows per page: _MENU_",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
          zeroRecords: "No matching records found"
        }
      });

      // Apply Bootstrap styles to search & length inputs
      $('.dataTables_filter input').addClass('form-control form-control-sm');
      $('.dataTables_length select').addClass('form-select form-select-sm');
    });
  </script>
@endpush
