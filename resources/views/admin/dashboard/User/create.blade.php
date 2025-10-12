@extends('admin.layout')

@section('title', 'Edit Users')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Users</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <form id="messageForm" action="{{ route('admin.users.store') }}" autocomplete="off" method="post">
            @csrf
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{ old('name') }}" name="name" id="name"
                                        class="form-control" placeholder="Name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Email</label>
                                    <input type="text" autocomplete="off" value="{{ old('email') }}" name="email" id="email"
                                        class="form-control" placeholder="Slug">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" value="{{ old('password') }}" name="password" id="password"
                                        class="form-control" placeholder="Password">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password">Confirm Password</label>
                                    <input type="password" value="{{ old('password_confirmation') }}" name="password_confirmation"
                                        id="password_confirmation" class="form-control" placeholder="Confirm Password">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active">Status</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Deactivate
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>

                                    <select id="role" name="role[]" class="form-control"
                                        data-placeholder="Select Roles" multiple data-multi-select>
                                        @if ($roles->count() == 0)
                                            <option value="">No Roles Found</option>
                                        @else
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" id="permSaveBtn" type="submit">Create</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div> <!-- /.container-fluid -->
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        // new MultiSelect('#dynamic', {
        //     data: [{
        //             value: 'opt1',
        //             text: 'Option 1'
        //         },
        //         {
        //             value: 'opt2',
        //             html: '<strong>Option 2 with HTML!</strong>'
        //         },
        //         {
        //             value: 'opt3',
        //             text: 'Option 3',
        //             selected: true
        //         },
        //         {
        //             value: 'opt4',
        //             text: 'Option 4'
        //         },
        //         {
        //             value: 'opt5',
        //             text: 'Option 5'
        //         }
        //     ],
        //     placeholder: 'Select an option',
        //     search: true,
        //     selectAll: true,
        //     listAll: false,
        //     max: 2,
        //     onChange: function(value, text, element) {
        //         console.log('Change:', value, text, element);
        //     },
        //     onSelect: function(value, text, element) {
        //         console.log('Selected:', value, text, element);
        //     },
        //     onUnselect: function(value, text, element) {
        //         console.log('Unselected:', value, text, element);
        //     }
        // });


        $(function() {
            const $form = $('#messageForm');
            const $btn = $('#permSaveBtn');

            $form.on('submit', function(e) {
                e.preventDefault();

                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.invalid-feedback').remove();

                const url = $form.attr('action');
                const data = $form.serialize();

                $btn.prop('disabled', true).text('Creating…');

                $.ajax({
                        url: url,
                        method: 'post',
                        data: data,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .done(function(res) {
                        if (typeof showPopup === 'function') {
                            showPopup(res.message || 'User Updated successfully!', 'success',
                                3000);
                        }
                        $form[0].reset();
                        setTimeout(function() {
                            window.location.href = "{{ route('admin.users.index') }}";
                        }, 400);
                    })
                    .fail(function(xhr) {
                        $btn.prop('disabled', false).text('Update');

                        const response = xhr.responseJSON || {};

                        // Clear old errors
                        $form.find('.is-invalid').removeClass('is-invalid');
                        $form.find('.invalid-feedback').remove();
                        $('#general-error').remove();

                        // Case 1: Laravel validation-style field errors
                        if (xhr.status === 422 && response.errors && typeof response.errors ===
                            'object') {
                            Object.keys(response.errors).forEach(function(field) {
                                const $input = $form.find(`[name="${field}"]`);
                                if ($input.length) {
                                    $input.addClass('is-invalid');
                                    $('<div class="invalid-feedback d-block"></div>')
                                        .text(response.errors[field][0])
                                        .insertAfter($input);
                                }
                            });
                        }


                        // Case 2: General single error message like "Role Not Found"
                        else if (response.errors && typeof response.errors === 'string') {
                            // Insert it at the top of the card body, full width, not inside row
                            const $alert = $(`
            <div id="general-error" class="alert alert-danger py-2 px-3 mb-3" style="font-weight:500;">
                ${response.errors}
            </div>
        `);
                            $form.find('.card-body').prepend($alert);
                        }

                        // Case 3: Unknown error fallback
                        else {
                            const $alert = $(`
            <div id="general-error" class="alert alert-danger py-2 px-3 mb-3" style="font-weight:500;">
                Something went wrong. Please try again.
            </div>
        `);
                            $form.find('.card-body').prepend($alert);
                        }
                    })
                    .always(function() {
                        $btn.prop('disabled', false).text('Create');
                    });
            });
        });
    </script>
@endpush
