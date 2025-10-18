@extends('admin.layout')

@section('title', 'Accounts - Create')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Account</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <form id="accountForm" method="post">
            @csrf
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Name</label>
                                <input type="text" name="name" id="name" onchange="generate_slug()"
                                    value="{{ $account->name }}" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Slug</label>
                                <input type="text" name="slug" id="slug" readonly class="form-control"
                                    value="{{ $account->slug }}" placeholder="Slug">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $cat->id == $account->category_id ? 'selected' : '' }}>{{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Subcategory</label>
                                <select name="subcategory_id" id="subcategory_id" class="form-control">
                                    <option value="">Select Subcategory</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Price</label>
                                <input type="number" name="price" value="{{ $account->price }}" class="form-control"
                                    placeholder="Price">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Account Status</label>
                                <select name="status_id" class="form-control">
                                    <option value="">Select Status</option>
                                    @foreach ($statuses as $st)
                                        <option value="{{ $st->id }}"
                                            {{ $st->id == $account->status_id ? 'selected' : '' }}>{{ $st->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select name="is_active" class="form-control">
                                    <option value="1" {{ $account->is_active == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $account->is_active == 0 ? 'selected' : '' }}>Deactivate
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Keywords</label>
                                <input type="text" id="keywords" class="form-control"
                                    placeholder="Type a keyword and press Enter">
                                <input type="hidden" id="tags" name="tags" value='{{ json_decode($account->tags) }}'>
                                <div id="keywords-container" class="mt-2"></div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Description">{{ $account->description }}</textarea>
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
                                    @if ($account->images)
                                        <div class="mt-3">
                                            @foreach ($account->images as $image)
                                                <img src="{{ asset('uploads/accounts/thumb/' . $image->image) }}"
                                                    alt="Image" width="100" class="mr-2 mb-2">
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" id="saveBtn" type="submit">Update</button>
                    <a href="" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        function generate_slug() {
            let name = $('#name').val().toLowerCase().trim().replace(/\s+/g, '-');
            let userId = "{{ auth()->id() }}";
            $('#slug').val(userId + '/' + name);
        }
        $(function() {
            // ================= TAG SYSTEM =================
            // ================= TAGS / KEYWORDS =================
            let tags = [];

            // ✅ Load existing tags (for edit)
            $(document).ready(function() {
                let raw = $('#tags').val();

                // Try to parse existing JSON safely
                try {
                    let existing = JSON.parse(raw || '[]');

                    
                    if (Array.isArray(existing)) {
                        tags = existing;
                        updateTagsUI();
                    }
                } catch (e) {
                    console.error("Invalid tag data:", raw);
                }
            });

            // Add new tag on Enter
            $('#keywords').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    let value = $(this).val().trim();

                    if (value && !tags.includes(value.toLowerCase())) {
                        if (tags.length >= 5) {
                            alert("You can only add up to 5 keywords.");
                            return;
                        }
                        tags.push(value.toLowerCase());
                        updateTagsUI();
                        $(this).val('');
                    }
                }
            });

            // Remove tag on click
            $(document).on('click', '.remove-tag', function() {
                let tag = $(this).data('tag');
                tags = tags.filter(t => t !== tag);
                updateTagsUI();
            });

            // Function to refresh the UI
            function updateTagsUI() {
                $('#keywords-container').html('');
                tags.forEach(tag => {
                    $('#keywords-container').append(`
            <span class="badge badge-primary mr-1 mb-1 p-2" style="background-color:#0e223e;color:white;">
                ${tag}
                <span class="ml-1 text-light remove-tag" data-tag="${tag}" style="cursor:pointer;">&times;</span>
            </span>
        `);
                });
                $('#tags').val(JSON.stringify(tags));
            }

        });


        $(function() {
            const $form = $('#accountForm');
            const $btn = $('#saveBtn');

            $form.on('submit', function(e) {
                e.preventDefault();
                $btn.prop('disabled', true).text('Saving…');

                $.ajax({
                    url: "{{ route('admin.accounts.update', $account->id) }}",
                    method: 'patch',
                    data: $form.serialize(),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function() {
                        window.location.href = "{{ route('admin.accounts.index') }}";
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $('.text-danger').remove();
                            $('input, textarea, select').removeClass('is-invalid');
                            for (let key in errors) {
                                const field = $('[name="' + key + '"]');
                                field.addClass('is-invalid');
                                field.after('<p class="text-danger text-sm">' + errors[key][0] +
                                    '</p>');
                            }
                        }
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Update');
                    }
                });
            });
        });



        // $('#category_id').on('change', function() {
        //     let categoryId = $(this).val();
        //     let subSelect = $('#subcategory_id');
        //     subSelect.empty().append('<option value="">Loading...</option>');

        //     if (categoryId) {
        //         $.ajax({
        //             url: "{{ url('/admin/get-sub-categories') }}/" + categoryId,
        //             type: 'GET',
        //             success: function(data) {
        //                 subSelect.empty().append('<option value="">Select Subcategory</option>');
        //                 if (data.length > 0) {
        //                     $.each(data, function(key, sub) {
        //                         subSelect.append('<option value="' + sub.id + '">' + sub.name +
        //                             '</option>');
        //                     });
        //                 } else {
        //                     subSelect.append('<option value="">No subcategories found</option>');
        //                 }
        //             },
        //             error: function() {
        //                 subSelect.empty().append(
        //                     '<option value="">Error loading subcategories</option>');
        //             }
        //         });
        //     } else {
        //         subSelect.empty().append('<option value="">Select Subcategory</option>');
        //     }
        // });


        $('#category_id').on('change', function() {
            let categoryId = $(this).val();
            let subSelect = $('#subcategory_id');
            subSelect.empty().append('<option value="">Loading...</option>');

            if (categoryId) {
                $.ajax({
                    url: "{{ url('/admin/get-sub-categories') }}/" + categoryId,
                    type: 'GET',
                    success: function(data) {
                        subSelect.empty().append('<option value="">Select Subcategory</option>');
                        if (data.length > 0) {
                            $.each(data, function(key, sub) {
                                subSelect.append('<option value="' + sub.id + '">' + sub.name +
                                    '</option>');
                            });
                        } else {
                            subSelect.append('<option value="">No subcategories found</option>');
                        }
                    },
                    error: function() {
                        subSelect.empty().append(
                            '<option value="">Error loading subcategories</option>');
                    }
                });
            } else {
                subSelect.empty().append('<option value="">Select Subcategory</option>');
            }
        });

        $(document).ready(function() {
            let currentCategoryId = "{{ $account->category_id }}";
            let currentSubcategoryId = "{{ $account->subcategory_id }}";

            if (currentCategoryId) {
                let subSelect = $('#subcategory_id');
                subSelect.empty().append('<option value="">Loading...</option>');

                $.ajax({
                    url: "{{ url('/admin/get-sub-categories') }}/" + currentCategoryId,
                    type: 'GET',
                    success: function(data) {
                        subSelect.empty().append('<option value="">Select Subcategory</option>');
                        if (data.length > 0) {
                            $.each(data, function(key, sub) {
                                let selected = (sub.id == currentSubcategoryId) ? 'selected' :
                                    '';
                                subSelect.append('<option value="' + sub.id + '" ' + selected +
                                    '>' + sub.name + '</option>');
                            });
                        } else {
                            subSelect.append('<option value="">No subcategories found</option>');
                        }
                    },
                    error: function() {
                        subSelect.empty().append(
                            '<option value="">Error loading subcategories</option>');
                    }
                });
            }
        });



        // Dropzone.autoDiscover = false;
        // const dropzone = $("#image").dropzone({
        //     init: function() {
        //         this.on('addedfile', function(file) {

        //         });
        //     },
        //     url: "{{ route('temp-images.create') }}",
        //     maxFiles: 5,
        //     maxFilesize: 3,
        //     paramName: 'image',
        //     addRemoveLinks: true,
        //     acceptedFiles: "image/jpeg,image/png,image/gif",
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     success: function(file, response) {
        //         $("#image_id").val(response.image_id);
        //         //console.log(response)
        //     }
        // });


        Dropzone.autoDiscover = false;

        const dropzone = $("#image").dropzone({
            url: "{{ route('temp-images.create') }}",
            maxFiles: 5,
            maxFilesize: 3, // 3 MB limit
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            init: function() {
                this.on('success', function(file, response) {
                    // Store the uploaded image ID inside the file object
                    file.image_id = response.image_id;

                    // Append new image ID to the hidden field
                    let ids = $("#image_id").val() ? $("#image_id").val().split(',') : [];
                    ids.push(response.image_id);
                    $("#image_id").val(ids.join(','));
                });

                this.on('removedfile', function(file) {
                    // Remove the corresponding image ID from the hidden field
                    if (file.image_id) {
                        let ids = $("#image_id").val().split(',').filter(Boolean);
                        ids = ids.filter(id => id !== String(file.image_id));
                        $("#image_id").val(ids.join(','));
                    }
                });

                this.on('error', function(file, message) {
                    if (file.size > 3 * 1024 * 1024) {
                        this.removeFile(file);
                        alert("File is too large. Maximum allowed size is 3 MB.");
                    } else {
                        alert(message);
                    }
                });
            }
        });
    </script>
@endpush
