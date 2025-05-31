@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Data Device</h1>
            <p class="text-gray-500 mt-1">Manajemen perangkat IoT yang terhubung</p>
        </div>
        <a href="/device/create"
           class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Data Device
        </a>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-md px-4 py-3 mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Device Table -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Device</h2>
            <span class="text-sm text-gray-500">
                Menampilkan {{ $devices->firstItem() }} hingga {{ $devices->lastItem() }} dari total {{ $devices->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wide text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Serial Number</th>
                        <th class="px-6 py-3 text-left">Meta Data</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($devices as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                {{ ($devices->currentPage() - 1) * $devices->perPage() + $loop->index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $item->serial_number }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $item->meta_data }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-3">
                                    <a href="/device/edit/{{ $item->id }}"
                                       class="text-yellow-600 hover:text-yellow-800" title="Ubah">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/device/delete/{{ $item->id }}" method="POST" onsubmit="return confirmDelete();">
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
            {{ $devices->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Summary Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-full mr-4">
                    <i class="fas fa-microchip text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Device Terdaftar</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $devices->total() }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Delete Confirmation Script -->
<script>
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus data device ini?');
    }
</script>
@endsection
