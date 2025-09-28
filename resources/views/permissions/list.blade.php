<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Permissions') }}
            </h2>
            <a href="{{ route('permissions.create') }}" class="bg-slate-300 text-sm rounded-md px-5 py-3"> Create New
                Permission</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('admin.components.message')
            </div>
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr class="border-b-2">
                        <th class="px-3 py-2 text-center" width="90">#</th>
                        <th class="px-3 py-2 text-left">Name</th>
                        <th class="px-3 py-2 text-left" width="180">Created At</th>
                        <th class="px-3 py-2 text-center" width="180">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($permissions->isNotEmpty())
                        @foreach ($permissions as $permission)
                            <tr>
                                <td class="px-3 py-2 text-center">{{ $permission->id }}</td>
                                <td class="px-3 py-2 text-left">{{ $permission->name }}</td>
                                <td class="px-3 py-2 text-left" >{{ $permission->created_at->format('d M,y') }}</td>
                                <td class="px-3 py-2 text-center space-x-2">
                                    <a href=""
                                        class="bg-gray-500 text-white text-sm rounded-md px-4 py-2">
                                        Edit
                                    </a>

                                        <button type="submit"
                                            class="bg-red-500 text-white text-sm rounded-md px-4 py-2"
                                            onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
