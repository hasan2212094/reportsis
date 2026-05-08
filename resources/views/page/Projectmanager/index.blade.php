@extends('kerangka.master')
@section('title', 'Project Manager Actual')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold mb-3">
            <span class="text-muted fw-light">Tables /</span> Project Manager
        </h4>

        @include('components.alert')

        <a href="{{ route('page.projectmanager.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow">
            + Tambah Data
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-xl border border-gray-100">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Workorder</th>
                        <th class="px-4 py-3 text-left">Workarea</th>
                        <th class="px-4 py-3 text-left">Perusahaan</th>
                        <th class="px-4 py-3 text-left">Project</th>
                        <th class="px-4 py-3 text-left">PM</th>
                        <th class="px-4 py-3 text-right">Qty</th>
                        <th class="px-4 py-3 text-center">Target</th>
                        <th class="px-4 py-3 text-center">Actual</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Progress</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                        <th class="px-4 py-3 text-center">Lampiran</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach ($projectmanagers as $persentasi)
                        <tr class="hover:bg-gray-50 transition">

                            <!-- ID -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $persentasi->id }}
                            </td>

                            <!-- WORKORDER -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium">
                                {{ $persentasi->workorder->nomer ?? '-' }}
                            </td>

                            <!-- WORKAREA -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $persentasi->workarea }}
                            </td>

                            <!-- PT -->
                            <td class="px-4 py-3 whitespace-nowrap truncate max-w-[150px]"
                                title="{{ $persentasi->pt->name ?? '-' }}">
                                {{ $persentasi->pt->name ?? '-' }}
                            </td>

                            <!-- PROJECT -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $persentasi->project }}
                            </td>

                            <!-- PM -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $persentasi->user_pm }}
                            </td>

                            <!-- QTY -->
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                {{ $persentasi->qty }}
                            </td>

                            <!-- TARGET -->
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($persentasi->target_date)->format('d M Y') }}
                            </td>

                            <!-- ACTUAL -->
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                {{ $persentasi->actualfinish_date
                                    ? \Carbon\Carbon::parse($persentasi->actualfinish_date)->format('d M Y')
                                    : '-' }}
                            </td>

                            <!-- STATUS -->
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if ($persentasi->status_pekerjaan == 0)
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 text-xs rounded">
                                        Pending
                                    </span>
                                @elseif ($persentasi->status_pekerjaan == 1)
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 text-xs rounded">
                                        Progress
                                    </span>
                                @else
                                    <span class="bg-green-100 text-green-700 px-2 py-1 text-xs rounded">
                                        Done
                                    </span>
                                @endif
                            </td>

                            <!-- PROGRESS -->
                            <td class="px-4 py-3 text-center">
                                <div class="w-32 mx-auto">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full"
                                            style="width: {{ $persentasi->persentase }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-600">
                                        {{ $persentasi->persentase }}%
                                    </span>
                                </div>
                            </td>

                            <!-- KETERANGAN -->
                            <td class="px-4 py-3 truncate max-w-[200px]" title="{{ $persentasi->keterangan }}">
                                {{ $persentasi->keterangan ?? '-' }}
                            </td>

                            <!-- LAMPIRAN -->
                            <td class="px-4 py-3 text-center">
                                @if ($persentasi->images_progress && $persentasi->images_progress->count())
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        @foreach ($persentasi->images_progress->take(3) as $img)
                                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                                onclick="openModal('{{ asset('storage/' . $img->image_path) }}')"
                                                class="w-10 h-10 object-cover rounded cursor-pointer hover:scale-110 transition">
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('presentasi.edit', $persentasi->id) }}"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">
                                        Edit
                                    </a>

                                    <a href="{{ route('presentasi.indexprogress', $persentasi->id) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                        Progress
                                    </a>

                                    <form action="{{ route('presentasi.destroy', $persentasi->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button onclick="return confirm('Yakin hapus data ini?')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="p-4">
            {{ $projectmanagers->links() }}
        </div>

    </div>

    </div>
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">

        <div class="relative">
            <img id="modalImage" class="max-w-[90vw] max-h-[80vh] rounded shadow-lg">

            <!-- tombol close -->
            <button onclick="closeModal()" class="absolute top-2 right-2 bg-white text-black px-3 py-1 rounded shadow">
                ✕
            </button>
        </div>

    </div>
    <script>
        function openModal(src) {
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
            document.getElementById('modalImage').src = src;
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }
    </script>
@endsection
