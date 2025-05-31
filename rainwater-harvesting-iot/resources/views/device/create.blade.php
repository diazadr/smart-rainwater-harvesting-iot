@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Data Device</h1>
    </div>

    <form method="POST" action="/device/store" class="bg-white border border-gray-200 shadow-sm rounded-lg p-6 space-y-6">
        @csrf

        <div>
            <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
            <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number') }}"
                class="px-3 py-2 mt-1 block w-full h-10 text-base rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('serial_number') border-red-500 @enderror">
            @error('serial_number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="meta_data" class="block text-sm font-medium text-gray-700">Meta Data</label>
            <input type="text" id="meta_data" name="meta_data" value="{{ old('meta_data') }}"
                class="px-3 py-2 mt-1 block w-full h-10 text-base rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('meta_data') border-red-500 @enderror">
            @error('meta_data')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow transition duration-200">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
