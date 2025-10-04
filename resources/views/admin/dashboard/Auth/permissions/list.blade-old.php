@extends('admin.layout')

@section('title', 'Permissions')

@section('content')
    <div class="container">
        <div class="container d-inline-flex justify-content-between  align-items-center gap-2 p-2 border rounded">
            <h4>Permissions</h4>
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#exampleModalCenter">Create
                New</button>
        </div>

        {{-- //html for create form popup model --}}
        <!-- Modal -->
        <!-- Your modal stays the same -->
        <div class="modal fade " id="exampleModalCenter" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content bg-theme">
                    <div class="modal-header">
                        <h5 class="modal-title text-light">Add New Permission</h5>
                        <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <form id="messageForm" data-action="{{ route('admin.permissions.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="perm-name" class="col-form-label text-light">Name:</label>
                                    <input type="text" name="name" class="form-control" id="perm-name">
                                </div>

                                <div class="form-group">
                                    <label for="perm-slug" class="col-form-label text-light">Slug:</label>
                                    <input type="text" name="slug" class="form-control" id="perm-slug">
                                </div>

                                <div class="form-group">
                                    <label for="perm-active" class="col-form-label text-light">Status:</label>
                                    <select class="form-control" id="perm-active" name="is_active">
                                        <option value="1">Active</option>
                                        <option value="0">Deactivate</option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="permSaveBtn">Save</button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- //html for create form popup model end --}}

    <div class="container pt-3 bg-light">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Permission Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $permission->name }}</td>
                        <td> {{ $permission->slug }}</td>
                        @if ($permission->is_active)
                            <td>
                                <svg class="text-success-500 text-success icons-active" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </td>
                            @else
                            <td>
												<svg class="text-danger icons-active" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
													<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
												</svg>
											</td>
                        @endif

                        <td>
												<a href="#">
													<svg class="filament-link-icon icons-active mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
														<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
													</svg>
												</a>
												<a href="#" class="text-danger icons-active mr-1">
													<svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon icons-active mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
														<path ath="" fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
												  	</svg>
												</a>
											</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            const $form = $('#messageForm');
            const $btn = $('#permSaveBtn');
            const $modal = $('#exampleModalCenter');

            $form.on('submit', function(e) {
                e.preventDefault();

                // clear previous validation UI
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.invalid-feedback').remove();

                const url = $form.data('action');
                const data = $form.serialize();

                $btn.prop('disabled', true).text('Saving…');

                $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .done(function(res) {
                        // success popup (you said showPopup is already working)
                        if (typeof showPopup === 'function') {
                            showPopup(res.message || 'Permission saved successfully!', 'success', 3000);
                        }
                        // reset + close
                        $form[0].reset();
                        $modal.modal('hide');
                        window.location.reload();

                        // TODO: if you want, refresh table or append new row here.
                        // location.reload(); // simplest way if you need immediate refresh
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            // Laravel validation errors -> mark fields
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
                        $btn.prop('disabled', false).text('Save');
                    });
            });

            $modal.on('hidden.bs.modal', function() {
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.invalid-feedback').remove();
            });
        });
    </script>
@endpush
