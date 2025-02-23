@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 bg-dark text-white vh-100">
                <div class="py-4 text-center">
                    <h2>Panel</h2>
                    <p class="small text-muted">Welcome, {{ Auth::user()->name }}</p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link text-white" href="">📊 Admin Panel</a>
                    <a class="nav-link text-white" href="{{ route('home') }}">🏚️ Home</a>
                    <a class="nav-link text-white" href="{{ route('settings') }}">⚙️ Settings</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="py-4">
                    <h1 class="text-primary">Admin Panel</h1>
                    <p class="text-muted">User management</p>

                    <!-- Cards -->
                    <div class="row g-4">
                        <div class="col-md-8" style="margin-left:15%">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title" style="text-align:center">Registered Users</h5>
                                    <p class="card-text fs-2" style="text-align:center">{{ $usersCount }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="mt-5">
                        <h2 id="registers" class="text-secondary">Last registers</h2>
                        <table class="table table-hover shadow-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <a href="{{ route('user.show', $user->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <!-- <a href="{{ route('user.destroy', $user->id) }}"
                                                                                                                class="btn btn-sm btn-danger deleteAction" data-id="{{ $user->id }}">Delete</a> -->
                                            @if ($user->id !== 1 && !(Auth::user()->role == 'admin' && $user->role == 'admin'))
                                                <a href="{{ route('user.destroy', $user->id) }}"
                                                    class="btn btn-sm btn-danger deleteAction" data-id="{{ $user->id }}">
                                                    Delete
                                                </a>
                                            @endif
                                            @if (is_null($user->email_verified_at) && Auth::user()->role != 'user')
                                                <form action="{{ route('user.verifyManually', $user->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">Verify</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="" class="d-none" id="formDelete">
        @csrf
        @method('DELETE')
    </form>

    <script>
        const deleteButtons = document.querySelectorAll('.deleteAction');
        const formDelete = document.getElementById('formDelete');

        function deleteForm() {
            deleteButtons.forEach((deleteButton) => {
                deleteButton.addEventListener('click', (event) => {
                    event.preventDefault();
                    if (confirm('Are you sure you want to delete this user?')) {
                        formDelete.action = event.target.href;
                        formDelete.submit();
                    }
                })
            })
        }

        deleteForm();

    </script>
@endsection