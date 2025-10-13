@extends('admin.layout')

@section('title', 'Categories - Create')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-primary">Back</a>
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
                                        value="{{ old('name') }}" class="form-control" placeholder="Name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" value="{{ old('slug') }}" name="slug" id="slug" readonly
                                        class="form-control" placeholder="Slug">

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active">Status</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Deactivate
                                        </option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>

                                    <select id="category_id" name="category_id" class="form-control">
                                        @if ($categories->count() == 0)
                                            <option value="">No categories Found</option>
                                        @else
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Description">{{ old('description') }}</textarea>
                                </div>
                            </div>

                             <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" name="image_id" id="image_id" value="">
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div> <!-- /.row -->
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" id="permSaveBtn" type="submit">Create</button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
                    url: "{{ route('admin.sub-categories.store') }}",
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        window.location.href = "{{ route('admin.sub-categories.index') }}";
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;

                            $('.text-danger.validation-error').remove();
                            $('#name, #slug, #description, #is_active, #category')
                                .removeClass('is-invalid');

                            const fieldMap = {
                                name: '#name',
                                slug: '#slug',
                                description: '#description',
                                is_active: '#is_active',
                                category_id: '#category_id' // ✅ FIXED
                            };

                            // 3) Render each error under the right field
                            Object.keys(errors).forEach(function(key) {
                                const selector = fieldMap[key];
                                if (!selector) return; // ignore unknown keys

                                const $field = $(selector);
                                $field.addClass('is-invalid');

                                // Put the error message right after the control
                                const msg = Array.isArray(errors[key]) ? errors[key][
                                    0
                                ] : errors[key];
                                $field.after(
                                    '<p class="text-danger text-sm validation-error">' +
                                    msg + '</p>'
                                );
                            });
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

         Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });

    </script>
@endpush
