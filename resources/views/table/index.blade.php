@extends('kerangka.master')
@section('title')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> User Tables</h4>

        <!-- Basic Bootstrap Table -->
        @php
            $no = 1;
        @endphp
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <a href="{{ route('table.create') }}" class="btn btn-primary rounded-pill">Tambah data</a>
            </div>
            <div class="table-responsive text-nowrap">
                <table id="example" class="table tabel-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>nama</th>
                            <th>email</th>
                            <th>users</th>
                            <th>role</th>
                            <th>status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($user as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                            class="avatar avatar-xs pull-up" title="{{ $item->name }}">
                                            @if ($item->role === 'admin')
                                                <img src="{{ asset('sneat/assets/img/avatars/5.png') }}" alt="Avatar"
                                                    class="rounded-circle" />
                                            @elseif($item->role === 'user')
                                                <img src="{{ asset('sneat/assets/img/avatars/6.png') }}" alt="Avatar"
                                                    class="rounded-circle" />
                                            @else
                                                <img src="{{ asset('sneat/assets/img/avatars/7.png') }}" alt="Avatar"
                                                    class="rounded-circle" />
                                            @endif
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <span
                                        class="badge 
                    @if ($item->role === 'admin') bg-label-primary
                    @elseif($item->role === 'user') bg-label-success
                    @else bg-label-info @endif me-1">
                                        {{ ucfirst($item->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-label-success me-1">Active</span>
                                </td>
                                <td>
                                    <a href="{{ route('table.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('table.destroy', $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin mau hapus user ini?')"
                                            class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        <hr class="my-5" />

    </div>
@endsection
