@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold">Edit Employee</h1>

    <div class="breadcrumb mt-3">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        Master
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="www.w3.org" fill="none"
                            viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <p class="ml-1 text-sm font-medium text-blue-600 md:ml-2">
                            Employee
                        </p>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="mt-6">
        <div class="bg-white shadow-md rounded-lg border border-gray-200">
            <div class="p-6">
                <form action="{{ route('employee.update', $employee->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                                Name Employee
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30
                            @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Position
                            </label>
                            <select name="position_id"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                                <option value="">-- Select Position --</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}"
                                        {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <input type="text" name="email" id="email"
                                value="{{ old('email', $employee->user->email) }}" placeholder="Ex: budisantoso@gmail.com"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30
                            @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <input type="password" name="password" id="password" placeholder="Password"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-12 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                            <small>Leave blank if you don't want to change it.</small>
                            <button type="button" id="togglePassword"
                                class="absolute right-3 top-12 -translate-y-1/2
                                text-gray-400 hover:text-blue-600 focus:outline-none">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                    <!-- eye-off -->
                                    <path class="eye-off" d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path class="eye-off" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7" />
                                    <path class="eye-off" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7" />
                                    <line class="eye-off" x1="2" y1="2" x2="22" y2="22" />

                                    <!-- eye -->
                                    <path class="eye hidden" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7Z" />
                                    <circle class="eye hidden" cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Join Date
                            </label>
                            <input type="text" id="join_date" name="join_date"
                                value="{{ old('join_date', $employee->join_date) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select name="status" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                                <option value="" disabled selected>-- Select Status --</option>
                                <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="resign"
                                    {{ old('status', $employee->status) == 'resign' ? 'selected' : '' }}>Resign</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('position.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700
                            bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </a>

                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 text-sm font-medium
                            text-white bg-blue-600 rounded-lg hover:bg-blue-700
                            focus:ring-4 focus:ring-blue-300">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                flatpickr("#join_date", {
                    dateFormat: "Y-m-d",
                    defaultDate: "{{ old('join_date', $employee->join_date) }}"
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                const input = document.getElementById('password');
                const button = document.getElementById('togglePassword');

                const eye = button.querySelectorAll('.eye');
                const eyeOff = button.querySelectorAll('.eye-off');

                button.addEventListener('click', () => {
                    const isPassword = input.type === 'password';

                    // toggle input type
                    input.type = isPassword ? 'text' : 'password';

                    // toggle icon
                    eye.forEach(el => el.classList.toggle('hidden', !isPassword));
                    eyeOff.forEach(el => el.classList.toggle('hidden', isPassword));
                });
            });
        </script>
    @endpush
@endsection
