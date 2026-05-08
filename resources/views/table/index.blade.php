@extends('kerangka.master')

@section('title', 'User Tables')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> User Tables
        </h4>

        @php $no = 1; @endphp

        <div class="card">

            <div class="card-header d-flex justify-content-end">
                <a href="{{ route('table.create') }}" class="btn btn-primary rounded-pill">
                    Tambah Data
                </a>
            </div>

            <div class="table-responsive text-nowrap">
                <table id="example" class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Avatar</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @foreach ($user as $item)
                            <tr>

                                {{-- NOMOR --}}
                                <td>{{ $no++ }}</td>

                                {{-- NAMA --}}
                                <td>{{ $item->name }}</td>

                                {{-- EMAIL --}}
                                <td>{{ $item->email }}</td>

                                {{-- AVATAR BERDASARKAN ROLE --}}
                                <td>
                                    <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                        <li class="avatar avatar-xs pull-up">

                                            @php
                                                $role = strtolower(optional($item->role)->name);

                                                $color = match ($role) {
                                                    'admin' => 'bg-primary',
                                                    'user' => 'bg-success',
                                                    'finance' => 'bg-warning',
                                                    'marketing' => 'bg-danger',
                                                    default => 'bg-secondary',
                                                };

                                                $initial = strtoupper(substr($item->name, 0, 1));
                                            @endphp

                                            <div class="avatar">
                                                <span class="avatar-initial rounded-circle {{ $color }}">
                                                    {{ $initial }}
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </td>

                                {{-- BADGE ROLE --}}
                                <td>
                                    @php
                                        $roleName = strtolower(optional($item->role)->name);
                                    @endphp

                                    <span
                                        class="badge
                                          @if ($roleName === 'admin') bg-label-primary
                                          @elseif ($roleName === 'user') bg-label-success
                                          @elseif ($roleName === 'finance') bg-label-warning
                                          @elseif ($roleName === 'marketing') bg-label-danger
                                          @else bg-label-secondary @endif
                                          me-1">
                                        {{ ucfirst(optional($item->role)->name ?? 'No Role') }}

                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="badge bg-label-success me-1">Active</span>
                                </td>

                                {{-- ACTION --}}
                                <td>
                                    <a href="{{ route('table.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('table.destroy', $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" onclick="return confirm('Yakin mau hapus user ini?')"
                                            class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>

    </div>
@endsection
