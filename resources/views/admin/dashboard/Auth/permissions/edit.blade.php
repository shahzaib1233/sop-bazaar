@extends('admin.layout')

@section('title', 'Permissions')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Permission</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <form id="messageForm" action="{{ route('admin.permissions.update', $permission->id) }}" method="post">
            @csrf
            @method('PATCH')
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name"
                                         onchange="generate_slug()" value="{{ old('name', $permission->name) }}"  class="form-control" placeholder="Name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" value="{{ old('slug', $permission->slug) }}" id="slug"
                                         readonly  class="form-control" placeholder="Slug">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="is_active">Status</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Deactivate</option>
                                    </select>
                                </div>
                            </div>

                        </div> <!-- /.row -->
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" id="permSaveBtn" type="submit">Update</button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div> <!-- /.container-fluid -->
        </form>
    </section>
@endsection

@push('scripts')
<script>

   function generate_slug() {
  let name = $('#name').val();
  let result = name.trim().replace(/\s+/g, '_'); 
  $('#slug').val(result);
 }

$(function () {
  const $form = $('#messageForm');
  const $btn  = $('#permSaveBtn');

  $form.on('submit', function(e){
    e.preventDefault();

    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').remove();

    const url  = $form.attr('action');
    const data = $form.serialize();

    $btn.prop('disabled', true).text('Saving…');

    $.ajax({
      url: url,
      method: 'patch',
      data: data,               
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json' 
      }
    })
    .done(function(res){
      if (typeof showPopup === 'function') {
        showPopup(res.message || 'Permission Updated successfully!', 'success', 3000);
      }
      $form[0].reset();
      setTimeout(function(){
        window.location.href = "{{ route('admin.permissions.index') }}";
      }, 400);
    })
    .fail(function(xhr){
      if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
        const errs = xhr.responseJSON.errors;
        Object.keys(errs).forEach(function(field){
          const $input = $form.find('[name="'+ field +'"]');
          if ($input.length) {
            $input.addClass('is-invalid');
            const fb = $('<div class="invalid-feedback"></div>').text(errs[field][0]);
            if ($input.next('.invalid-feedback').length === 0) {
              $input.after(fb);
            }
          }
        });
        if (typeof showPopup === 'function') {
          showPopup('Please fix the highlighted errors.', 'warning', 3500);
        }
      } else {
        if (typeof showPopup === 'function') {
          showPopup('Something went wrong. Please try again.', 'danger', 3500);
        }
      }
    })
    .always(function(){
      $btn.prop('disabled', false).text('Create');
    });
  });
});
</script>
@endpush
