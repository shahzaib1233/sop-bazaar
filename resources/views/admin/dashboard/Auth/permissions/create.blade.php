@extends('admin.layout')

@section('title', 'Create Permissions')

@section('content')
    <div class="">
        <section style="background-color: #eee;">
            <div class="container py-5">
                <div class="row">
                    @include('admin.components.message')
                    <div class="col">
                        <div class="container d-flex ">
                            <div class="card shadow-sm border-0" style="max-width: 400px; width: 100%;">
                                <div class="card-body p-4">
                                    <h4 class=" mb-4">Create Permission</h4>

                                    <form action="{{ route('admin.permissions.store') }}" method="POST">
                                        @csrf <div class="mb-3">
                                            <label for="permissionName" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="permissionName" name="name"
                                                placeholder="Enter permission name" required>
                                            @error('name')
                                                <p class="text-danger font-medium">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success">
                                                Create
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
