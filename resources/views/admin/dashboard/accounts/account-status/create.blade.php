@extends('admin.layout')

@section('title', 'Status - Create')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Status</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.status.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <form id="messageForm" method="post">
            @csrf
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" onchange="generate_slug()"
                                        class="form-control" placeholder="Name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" readonly class="form-control"
                                        placeholder="Slug">
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Description"></textarea>
                                </div>
                            </div>


                        </div> <!-- /.row -->
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" id="permSaveBtn" type="submit">Create</button>
                    <a href="{{ route('admin.status.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div> <!-- /.container-fluid -->
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        function generate_slug() {
            let name = $('#name').val().toLowerCase();

            let result = name.trim().replace(/\s+/g, '-');
            $('#slug').val(result);
        }

        $(function() {
            const $form = $('#messageForm');
            const $btn = $('#permSaveBtn');

            $form.on('submit', function(e) {
                e.preventDefault();
                $btn.prop('disabled', true).text('Saving…');

                $.ajax({
                    url: "{{ route('admin.categories.store') }}",
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        window.location.href = "{{ route('admin.categories.index') }}";
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            $('.text-danger').remove();
                            $('#name, #slug, #description, #is_active').removeClass(
                                'is-invalid');

                            if (errors.name) {
                                $('#name_error').text(errors.name[0]);
                                $('#name').addClass('is-invalid');
                                $('#name').after(
                                    '<p class="text-danger text-sm" id="name_error">' +
                                    errors.name[0] + '</p>');
                            }
                            if (errors.slug) {
                                $('#slug_error').text(errors.slug[0]);
                                $('#slug').addClass('is-invalid');
                                $('#slug').after(
                                    '<p class="text-danger text-sm" id="slug_error">' +
                                    errors.slug[0] + '</p>');
                            }
                            if (errors.description) {
                                $('#description_error').text(errors.description[0]);
                                $('#description').addClass('is-invalid');
                                $('#description').after(
                                    '<p class="text-danger text-sm" id="description_error">' +
                                    errors.description[0] + '</p>');
                            }
                            if (errors.is_active) {
                                $('#is_active_error').text(errors.is_active[0]);
                                $('#is_active').addClass('is-invalid');
                                $('#is_active').after(
                                    '<p class="text-danger text-sm" id="is_active_error">' +
                                    errors.is_active[0] + '</p>');
                            }
                        } else {
                            console.error('Something went wrong', xhr);
                        }
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Create');
                    }
                });
            });
        });


    </script>
@endpush
