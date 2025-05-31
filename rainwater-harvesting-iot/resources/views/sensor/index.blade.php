@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dasbor Sensor</h1>
            <p class="text-gray-500 mt-1">Pemantauan Sensor Suhu IoT Realtime</p>
        </div>
        <a href="/sensor/create"
           class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Data Sensor
        </a>
    </div>

    <!-- Welcome Alert -->
    <div x-data="{ show: true }" x-show="show"
         class="bg-blue-50 border border-blue-200 text-blue-800 rounded-md px-4 py-3 mb-6 flex justify-between items-center">
        <div>
            <i class="fas fa-info-circle mr-2"></i>
            Selamat datang kembali, <span class="font-semibold">{{ auth()->user()->name }}</span>.
            Anda memiliki <strong>{{ $sensor->total() }}</strong> data sensor.
        </div>
        <button @click="show = false" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-md px-4 py-3 mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Sensor Data Table -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden mb-8">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Data Sensor</h2>
            <span class="text-sm text-gray-500">
                Menampilkan {{ $sensor->firstItem() }} hingga {{ $sensor->lastItem() }} dari total {{ $sensor->total() }} data
            </span>
        </div>

        <!-- Filter Form -->
        <div class="overflow-x-auto">
            <form method="GET" action="{{ route('sensor.index') }}"
                  class="mb-6 flex flex-col md:flex-row md:items-center gap-4 px-6 pt-4">
                <div class="flex items-center gap-2">
                    <label for="filter" class="text-sm text-gray-700">Filter Nama Sensor:</label>
                    <select name="filter" id="filter" onchange="this.form.submit()"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">-- Semua --</option>
                        <option value="suhu" {{ request('filter') == 'suhu' ? 'selected' : '' }}>Suhu</option>
                        <option value="kelembapan" {{ request('filter') == 'kelembapan' ? 'selected' : '' }}>Kelembapan</option>
                        <option value="jarak" {{ request('filter') == 'jarak' ? 'selected' : '' }}>Jarak</option>
                        <option value="pompa" {{ request('filter') == 'pompa' ? 'selected' : '' }}>Pompa</option>
                        <option value="status_tabung" {{ request('filter') == 'status_tabung' ? 'selected' : '' }}>Status Tabung</option>
                        <option value="status_koneksi" {{ request('filter') == 'status_koneksi' ? 'selected' : '' }}>Status Koneksi</option>
                        <option value="lainnya" {{ request('filter') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </form>

            <!-- Table Content -->
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wide text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Nama Sensor</th>
                        <th class="px-6 py-3 text-left">Nilai</th>
                        <th class="px-6 py-3 text-left">Topik</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($sensor as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                {{ ($sensor->currentPage() - 1) * $sensor->perPage() + $loop->index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_sensor }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $item->id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-800 font-semibold">{{ $item->data }}</div>
                                <div class="text-xs text-gray-400">Diperbarui {{ $item->updated_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                    {{ $item->topic }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-3">
                                    <a href="/sensor/edit/{{ $item->id }}"
                                      class="text-yellow-600 hover:text-yellow-800" title="Ubah">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/sensor/delete/{{ $item->id }}" method="POST"
                                          onsubmit="return confirmDelete();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $sensor->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Summary Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-full mr-4">
                    <i class="fas fa-database text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Data Sensor</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $sensor->total() }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Delete Confirmation Script -->
<script>
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus data sensor ini?');
    }
</script>
@endsection
