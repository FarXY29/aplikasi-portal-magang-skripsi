<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Pembimbing Lapangan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-6">
            
            <!-- Form Tambah Pembimbing Lapangan -->
            <div class="w-1/3">
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <h3 class="font-bold mb-4">Tambah Pegawai (Pembimbing Lapangan)</h3>
                    <form action="{{ route('dinas.pembimbing_lapangan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="text-xs font-bold">Nama Pegawai</label>
                            <input type="text" name="name" class="w-full border-gray-300 rounded text-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-bold">NIP</label>
                            <input type="text" name="nip" class="w-full border-gray-300 rounded text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-bold">Email Login</label>
                            <input type="email" name="email" class="w-full border-gray-300 rounded text-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-bold">Password</label>
                            <input type="password" name="password" class="w-full border-gray-300 rounded text-sm" required>
                        </div>
                        <button class="w-full bg-teal-600 text-white py-2 rounded font-bold hover:bg-teal-700">Simpan Akun</button>
                    </form>
                </div>
            </div>

            <!-- List Pembimbing Lapangan -->
            <div class="w-2/3 bg-white p-6 shadow-sm rounded-lg">
                <h3 class="font-bold mb-4">Daftar Pembimbing Lapangan</h3>
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="pb-2">Nama / NIP</th>
                            <th class="pb-2">Email</th>
                            <th class="pb-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembimbing_lapangan as $pembimbing_lapangan)
                        <tr class="border-b">
                            <td class="py-3">
                                <div class="font-bold">{{ $pembimbing_lapangan->name }}</div>
                                <div class="text-xs text-gray-500">{{ $pembimbing_lapangan->nik }}</div>
                            </td>
                            <td>{{ $pembimbing_lapangan->email }}</td>
                            <td>
                                <form action="{{ route('dinas.pembimbing_lapangan.destroy', $pembimbing_lapangan->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>