<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg hidden md:block">
            <div class="p-6 font-bold text-xl border-b">
                Admin Panel
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('admin') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100 active:bg-gray-200">
                    Data Pengajuan
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">

            <!-- Navbar -->
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-lg font-semibold">Dashboard</h1>

                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600">Admin</span>
                    <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6" x-data="dialogModal()">

                <!-- Card -->
                <div class="bg-white p-6 rounded-2xl shadow">

                    <h2 class="text-xl font-bold mb-4">
                        Data Pengajuan Nasabah
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-gray-600">

                            <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left">Nama</th>
                                    <th class="px-4 py-3 text-left">Tipe</th>
                                    <th class="px-4 py-3 text-left">Nominal</th>
                                    <th class="px-4 py-3 text-left">Tenor</th>
                                    <th class="px-4 py-3 text-left">Tagihan/Bulan</th>
                                    <th class="px-4 py-3 text-left">Tanggal</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">

                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-800">
                                        Budi Santoso
                                    </td>
                                    <td class="px-4 py-3">Mobil</td>
                                    <td class="px-4 py-3">Rp 150.000.000</td>
                                    <td class="px-4 py-3">24 bulan</td>
                                    <td class="px-4 py-3 text-blue-600 font-semibold">
                                        Rp 6.250.000
                                    </td>
                                    <td class="px-4 py-3">20 Apr 2026</td>

                                    <!-- Status -->
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                            Pending
                                        </span>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2 justify-center">

                                            <button @click="openConfirm('approve', 'Budi Santoso')"
                                                class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600">
                                                Setujui
                                            </button>

                                            <button @click="openConfirm('reject', 'Budi Santoso')"
                                                class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600">
                                                Tolak
                                            </button>

                                            <button @click="
                                                showDetail = true;
                                                data = {
                                                    nama: 'Budi Santoso',
                                                    tipe: 'Mobil',
                                                    nominal: 150000000,
                                                    tenor: 24,
                                                    tanggal: '2026-04-20',
                                                    pendapatan: 8000000,
                                                    catatan: 'Pengajuan cepat'
                                                }
                                            "
                                                class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">
                                                Detail
                                            </button>

                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div x-show="showDetail"
                            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                            <div class="bg-white w-full max-w-lg rounded-2xl p-6 shadow-lg">

                                <h2 class="text-xl font-bold mb-4">
                                    Detail Pengajuan
                                </h2>

                                <div class="space-y-3 text-sm">

                                    <p><b>Nama:</b> <span x-text="data.nama"></span></p>
                                    <p><b>Tipe:</b> <span x-text="data.tipe"></span></p>
                                    <p><b>Nominal:</b>
                                        Rp
                                        <span x-text="(data.nominal ?? 0).toLocaleString('id-ID')"></span>
                                    </p>
                                    <p><b>Tenor:</b> <span x-text="data.tenor"></span> bulan</p>

                                    <p><b>Pendapatan:</b>
                                        Rp
                                        <span x-text="(data.pendapatan ?? 0).toLocaleString('id-ID')"></span>

                                    </p>

                                    <p><b>Tanggal:</b> <span x-text="data.tanggal"></span></p>
                                    <p><b>Catatan:</b> <span x-text="data.catatan"></span></p>

                                    <!-- Kalkulasi -->
                                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                        <p class="font-semibold text-blue-700">
                                            Tagihan per Bulan:
                                        </p>

                                        <p class="text-lg font-bold text-blue-600">
                                            Rp
                                            <span x-text="
                                                data.nominal && data.tenor 
                                                ? Math.floor(data.nominal / data.tenor).toLocaleString('id-ID') 
                                                : 0
                                            "></span>
                                        </p>
                                    </div>

                                </div>

                                <!-- Action -->
                                <div class="mt-6 flex justify-end gap-2">
                                    <button @click="showDetail = false"
                                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                                        Tutup
                                    </button>
                                </div>

                            </div>
                        </div>
                        <div x-show="show" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                            <!-- Dialog -->
                            <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-lg">

                                <h2 class="text-lg font-bold mb-2">
                                    Konfirmasi
                                </h2>

                                <p class="text-sm text-gray-600 mb-4">
                                    Yakin ingin
                                    <span class="font-semibold"
                                        :class="action === 'approve' ? 'text-green-600' : 'text-red-600'">
                                        <span x-text="action === 'approve' ? 'MENYETUJUI' : 'MENOLAK'"></span>
                                    </span>
                                    pengajuan dari
                                    <span class="font-semibold" x-text="name"></span>?
                                </p>

                                <!-- Buttons -->
                                <div class="flex justify-end gap-2">

                                    <button @click="close()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                                        Batal
                                    </button>

                                    <button @click="confirmAction()" :class="action === 'approve' 
                        ? 'bg-green-500 hover:bg-green-600' 
                        : 'bg-red-500 hover:bg-red-600'" class="px-4 py-2 text-white rounded">

                                        Ya, Lanjutkan
                                    </button>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </main>

        </div>

    </div>
    <script>
        function dialogModal() {
            return {
                showDetail: false,
                data: {},
                show: false,
                action: null,
                name: '',
                id: null,

                openConfirm(type, name, id = null) {
                    this.show = true
                    this.action = type
                    this.name = name
                    this.id = id
                },

                close() {
                    this.show = false
                },

                confirmAction() {
                    this.show = false

                    alert(`Aksi ${this.action} untk ${this.name}`)
                }
            }
        }
    </script>

</body>

</html>