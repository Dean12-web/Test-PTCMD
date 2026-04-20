<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

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

        <div class="flex-1 flex flex-col">
            <main class="p-6" x-data="dialogModal()" x-init="init()">
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold mb-4">Data Pengajuan Nasabah</h2>

                    <div class="mb-4 flex flex-col gap-3 md:flex-row md:justify-between md:items-center">
                        <input
                            type="text"
                            x-model="search"
                            @input.debounce.400ms="page = 1; loadApplication()"
                            placeholder="Cari nama / tipe..."
                            class="px-4 py-2 border rounded-lg w-full md:w-64 focus:ring-2 focus:ring-blue-500"
                        >

                        <select
                            x-model="perPage"
                            @change="page = 1; loadApplication()"
                            class="px-3 py-2 border rounded-lg w-full md:w-auto"
                        >
                            <option value="5">5 data</option>
                            <option value="10">10 data</option>
                            <option value="20">20 data</option>
                        </select>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-gray-600">
                            <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                                <tr>
                                    <th @click="sortBy('name')" class="px-4 py-3 text-left cursor-pointer">Nama</th>
                                    <th @click="sortBy('application_type')" class="px-4 py-3 text-left cursor-pointer">Tipe</th>
                                    <th class="px-4 py-3 text-left">Nominal</th>
                                    <th @click="sortBy('tenor')" class="px-4 py-3 text-left cursor-pointer">Tenor</th>
                                    <th class="px-4 py-3 text-left">Tagihan/Bulan</th>
                                    <th class="px-4 py-3 text-left">Tanggal</th>
                                    <th @click="sortBy('status')" class="px-4 py-3 text-left cursor-pointer">Status</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">
                                <template x-if="rows.length === 0">
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">Belum ada data pengajuan.</td>
                                    </tr>
                                </template>

                                <template x-for="row in rows" :key="row.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-800" x-text="row.name"></td>
                                        <td class="px-4 py-3" x-text="labelType(row.application_type)"></td>
                                        <td class="px-4 py-3" x-text="formatRupiah(row.nominal)"></td>
                                        <td class="px-4 py-3" x-text="`${row.tenor} bulan`"></td>
                                        <td class="px-4 py-3 text-blue-600 font-semibold" x-text="formatRupiah(row.monthly_installment)"></td>
                                        <td class="px-4 py-3" x-text="row.tanggal"></td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full"
                                                :class="statusClass(row.status)"
                                                x-text="statusLabel(row.status)"
                                            ></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-2 justify-center">
                                                <button
                                                    @click="openConfirm('approve', row.name, row.id)"
                                                    class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600"
                                                >
                                                    Setujui
                                                </button>
                                                <button
                                                    @click="openConfirm('reject', row.name, row.id)"
                                                    class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600"
                                                >
                                                    Tolak
                                                </button>
                                                <button
                                                    @click="openDetail(row)"
                                                    class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600"
                                                >
                                                    Detail
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col gap-3 md:flex-row md:justify-between md:items-center mt-4">
                        <p class="text-sm text-gray-500" x-text="paginationText()"></p>

                        <div class="flex items-center gap-1">
                            <button
                                @click="goTo((pagination.current_page || 1) - 1)"
                                :disabled="!pagination.current_page || pagination.current_page <= 1"
                                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Prev
                            </button>

                            <template x-for="p in pages()" :key="p">
                                <button
                                    @click="goTo(p)"
                                    :class="Number(p) === Number(pagination.current_page)
                                        ? 'px-3 py-1 bg-blue-500 text-white rounded'
                                        : 'px-3 py-1 bg-gray-200 rounded hover:bg-gray-300'"
                                    x-text="p"
                                ></button>
                            </template>

                            <button
                                @click="goTo((pagination.current_page || 1) + 1)"
                                :disabled="!pagination.current_page || pagination.current_page >= pagination.last_page"
                                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    x-show="showDetail"
                    x-cloak
                    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
                >
                    <div class="bg-white w-full max-w-lg rounded-2xl p-6 shadow-lg">
                        <h2 class="text-xl font-bold mb-4">Detail Pengajuan</h2>

                        <div class="space-y-3 text-sm">
                            <p><b>Nama:</b> <span x-text="data.name"></span></p>
                            <p><b>Tipe:</b> <span x-text="labelType(data.application_type)"></span></p>
                            <p><b>Nominal:</b> <span x-text="formatRupiah(data.nominal)"></span></p>
                            <p><b>Tenor:</b> <span x-text="data.tenor"></span> bulan</p>
                            <p><b>Pendapatan:</b> <span x-text="formatRupiah(data.pendapatan)"></span></p>
                            <p><b>Tanggal:</b> <span x-text="data.tanggal"></span></p>
                            <p><b>Catatan:</b> <span x-text="data.notes || '-'"></span></p>

                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <p class="font-semibold text-blue-700">Tagihan per Bulan:</p>
                                <p class="text-lg font-bold text-blue-600" x-text="formatRupiah(data.monthly_installment)"></p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-2">
                            <button @click="showDetail = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    x-show="show"
                    x-cloak
                    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
                >
                    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-lg">
                        <h2 class="text-lg font-bold mb-2">Konfirmasi</h2>

                        <p class="text-sm text-gray-600 mb-4">
                            Yakin ingin
                            <span class="font-semibold" :class="action === 'approve' ? 'text-green-600' : 'text-red-600'">
                                <span x-text="action === 'approve' ? 'MENYETUJUI' : 'MENOLAK'"></span>
                            </span>
                            pengajuan dari
                            <span class="font-semibold" x-text="name"></span>?
                        </p>

                        <div class="flex justify-end gap-2">
                            <button @click="close()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                            <button
                                @click="confirmAction()"
                                :class="action === 'approve' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600'"
                                class="px-4 py-2 text-white rounded"
                            >
                                Ya, Lanjutkan
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function dialogModal() {
            return {
                rows: [],
                pagination: {},
                search: '',
                perPage: 10,
                sort: 'created_at',
                direction: 'desc',
                page: 1,
                showDetail: false,
                data: {},
                show: false,
                action: null,
                name: '',
                id: null,

                init() {
                    this.loadApplication()
                },

                async loadApplication() {
                    const params = new URLSearchParams({
                        search: this.search,
                        per_page: this.perPage,
                        sort: this.sort,
                        direction: this.direction,
                        page: this.page,
                    })

                    const res = await fetch(`/application-view?${params.toString()}`)
                    const data = await res.json()

                    this.rows = data.rows || []
                    this.pagination = data.pagination || {}
                    this.page = this.pagination.current_page || 1
                },

                sortBy(field) {
                    if (this.sort === field) {
                        this.direction = this.direction === 'asc' ? 'desc' : 'asc'
                    } else {
                        this.sort = field
                        this.direction = 'asc'
                    }

                    this.page = 1
                    this.loadApplication()
                },

                goTo(page) {
                    if (!this.pagination.last_page) {
                        return
                    }

                    if (page < 1 || page > this.pagination.last_page) {
                        return
                    }

                    this.page = page
                    this.loadApplication()
                },

                pages() {
                    const total = this.pagination.last_page || 1
                    const current = this.pagination.current_page || 1
                    const start = Math.max(1, current - 2)
                    const end = Math.min(total, start + 4)
                    const first = Math.max(1, end - 4)
                    const out = []

                    for (let i = first; i <= end; i += 1) {
                        out.push(i)
                    }

                    return out
                },

                paginationText() {
                    if (!this.pagination.total) {
                        return 'Belum ada data'
                    }

                    return `Menampilkan ${this.pagination.from} - ${this.pagination.to} dari ${this.pagination.total} data`
                },

                labelType(type) {
                    const labels = {
                        motor: 'Sepeda Motor',
                        mobil: 'Mobil',
                        multiguna: 'Multiguna',
                    }

                    return labels[type] || type || '-'
                },

                statusLabel(status) {
                    const labels = {
                        pending: 'Pending',
                        approved: 'Approved',
                        rejected: 'Rejected',
                    }

                    return labels[status] || status || '-'
                },

                statusClass(status) {
                    if (status === 'approved') return 'bg-green-100 text-green-700'
                    if (status === 'rejected') return 'bg-red-100 text-red-700'
                    return 'bg-yellow-100 text-yellow-700'
                },

                formatRupiah(amount) {
                    const value = Number(amount || 0)
                    return `Rp ${value.toLocaleString('id-ID')}`
                },

                openDetail(row) {
                    this.data = row
                    this.showDetail = true
                },

                openConfirm(type, name, id = null) {
                    this.show = true
                    this.action = type
                    this.name = name
                    this.id = id
                },

                close() {
                    this.show = false
                },

                async confirmAction() {
                    try {
                        if (!this.id || !this.action) {
                            this.show = false
                            return
                        }

                        const endpoint = this.action === 'approve'
                            ? `/application-approve/${this.id}`
                            : `/application-reject/${this.id}`

                        const res = await fetch(endpoint, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        })

                        const result = await res.json()
                        alert(result.message || 'Aksi selesai diproses.')

                        if (res.ok && result.success) {
                            this.loadApplication()
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan saat memproses aksi.')
                    } finally {
                        this.show = false
                    }
                },
            }
        }
    </script>

</body>

</html>
