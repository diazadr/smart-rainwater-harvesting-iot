@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Data Sensor</h1>
    </div>

   <form method="POST" action="/sensor/store" class="bg-white border border-gray-200 shadow-sm rounded-lg p-6 space-y-6">
    @csrf
    <div>
        <label for="nama_sensor" class="block text-sm font-medium text-gray-700">Nama Sensor</label>
        <input type="text" id="nama_sensor" name="nama_sensor" value="{{ old('nama_sensor') }}"
            class="px-3 py-2 mt-1 block w-full h-10 text-base rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama_sensor') border-red-500 @enderror">
        @error('nama_sensor')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="data" class="block text-sm font-medium text-gray-700">Data</label>
        <input type="text" id="data" name="data" value="{{ old('data') }}"
            class="px-3 py-2 mt-1 block w-full h-10 text-base rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('data') border-red-500 @enderror">
        @error('data')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="topic" class="block text-sm font-medium text-gray-700">Topik</label>
        <input type="text" id="topic" name="topic" value="{{ old('topic') }}"
            class="px-3 py-2 mt-1 block w-full h-10 text-base rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('topic') border-red-500 @enderror">
        @error('topic')
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
