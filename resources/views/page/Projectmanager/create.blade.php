@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto mt-10">

        <div class="bg-white shadow-lg rounded-xl border border-gray-200">

            <!-- Header -->
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">
                    Tambah Pekerjaan
                </h2>
                <p class="text-sm text-gray-500">
                    Silakan isi data pekerjaan baru
                </p>
            </div>
            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg shadow">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ERROR --}}
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg shadow">
                    {{ session('error') }}
                </div>
            @endif

            {{-- VALIDATION ERROR --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg shadow">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('projectmanager.store') }}" method="POST">
                @csrf

                <div class="p-6 grid grid-cols-2 gap-5">

                    <!-- Workorder -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Workorder
                        </label>
                        <select name="workorder_id" class="w-full mt-1 px-4 py-2 border rounded-lg">
                            <option value="">--Pilih WO--</option>
                            @foreach ($workorders as $workorder)
                                <option value="{{ $workorder->id }}">
                                    {{ $workorder->kode_wo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Work Area -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Date Awal
                        </label>
                        <input type="date" name="date_awal" class="w-full mt-1 px-4 py-2 border rounded-lg">
                    </div>

                    <!-- Project -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Target Date
                        </label>
                        <input type="date" name="target_date" class="w-full mt-1 px-4 py-2 border rounded-lg">
                    </div>

                    <!-- PM -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Persentase
                        </label>
                        <input type="integer" name="persentase_A" class="w-full mt-1 px-4 py-2 border rounded-lg">
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">

                    <a href="{{ route('page.Projectmanager.index') }}"
                        class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                        Batal
                    </a>

                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection
