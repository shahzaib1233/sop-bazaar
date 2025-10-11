@extends('admin.layout')

@section('title', 'Create Roles')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Roles</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <form id="messageForm" action="{{ route('admin.roles.update', $users->id) }}" method="post">
            @csrf
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{ old('name', $users->name) }}" name="name" id="name" onchange="generate_slug()"
                                        class="form-control" placeholder="Name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Email</label>
                                    <input type="text"  value="{{ old('email', $users->email) }}" name="email" id="email" class="form-control"
                                        placeholder="Slug">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active">Status</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="1" {{ $users->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$users->is_active ? 'selected' : '' }} >Deactivate</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="permissions" class="form-label">Permissions</label>

                                    <select id="permissions" name="permissions" data-placeholder="Select Permissions" multiple
                                        data-multi-select>
                                        {{-- @if ($permissions && $permissions->count() == 0)
                                            <option value="">No Permissions Found</option>
                                        @else
                                            @foreach ($permissions as $permission)
                                                <option value="{{ $permission->id }}" {{ in_array($permission->id, $usersPermissions) ? 'selected' : '' }}>{{ $permission->name }}</option>
                                            @endforeach
                                        @endif --}}
                                         @if ($roles && $roles->count() == 0)
                                            <option value="">No Roles Found</option>
                                        @else
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" >{{ $role->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> 

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" id="permSaveBtn" type="submit">Update</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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

                $btn.prop('disabled', true).text('Updating…');

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
                    .done(function(res) {
                        if (typeof showPopup === 'function') {
                            showPopup(res.message || 'Permission Updated successfully!', 'success', 3000);
                        }
                        $form[0].reset();
                        setTimeout(function() {
                            window.location.href = "{{ route('admin.roles.index') }}";
                        }, 400);
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            Object.keys(errs).forEach(function(field) {
                                const $input = $form.find('[name="' + field + '"]');
                                if ($input.length) {
                                    $input.addClass('is-invalid');
                                    const fb = $('<div class="invalid-feedback"></div>').text(
                                        errs[field][0]);
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
                    .always(function() {
                        $btn.prop('disabled', false).text('Create');
                    });
            });
        });
    </script>
@endpush
