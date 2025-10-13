@extends('admin.layout')

@section('title', 'Sub Categories - Edit')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Back</a>
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
                                    <input type="text" name="name" value="{{ old('name' , $sub_category->name) }}" id="name" onchange="generate_slug()"
                                        class="form-control" placeholder="Name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text"  value="{{ old('slug' , $sub_category->slug) }}" name="slug" id="slug" readonly class="form-control"
                                        placeholder="Slug">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active">Status</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="1" {{ old('is_active', $sub_category->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $sub_category->is_active) == 0 ? 'selected' : '' }}>Deactivate</option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id">Category</label>
                                    <select id="category_id" name="category_id" class="form-control">
                                        @if ($categories->count() == 0)
                                            <option value="">No categories Found</option>
                                        @else
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ $sub_category->category_id == $category->id ? 'selected' : '' }}>
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
                                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Description">{{ old('description', $sub_category->description) }}</textarea>
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
                                @if ($sub_category->image)
                                    <div class="mb-3" id="show-image">
                                        <img src="{{ asset('uploads/sub_categories/thumb/' . $sub_category->image) }}"
                                            alt="Sub Category Image" style="max-width: 150px; max-height: 150px;">
                                    </div>
                                    @endif
                            </div>

                        </div> <!-- /.row -->
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" id="permSaveBtn" type="submit">Update</button>
                    <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
                $btn.prop('disabled', true).text('Updating…');

                $.ajax({
                    url: "{{ route('admin.sub-categories.update', $sub_category->id ) }}",
                    method: 'patch',
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
                        $btn.prop('disabled', false).text('Updating');
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
                $('#show-image').addClass('d-none');
                //console.log(response)
            }
        });

    </script>
@endpush
