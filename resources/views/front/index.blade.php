<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Pengajuan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
            Form Pengajuan Pembiayaan
        </h2>

        <form class="space-y-5" enctype="multipart/form-data"  x-data="applicationForm()" @submit.prevent="submitForm">
            @csrf
            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Lengkap
                </label>
                <input type="text" x-model="name"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Masukkan nama lengkap">
                <p x-show="errors.name" class="text-red-500 text-sm mt-1">
                    <span x-text="errors.name"></span>
                </p>
            </div>

            <!-- Tipe Pengajuan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tipe Pengajuan
                </label>
                <select x-model="application_type"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Pilih tipe</option>
                    <option value="motor">Sepeda Motor</option>
                    <option value="mobil">Mobil</option>
                    <option value="multiguna">Multiguna</option>
                </select>
                <p x-show="errors.application_type" class="text-red-500 text-sm mt-1">
                    <span x-text="errors.application_type"></span>
                </p>
            </div>

            <!-- Nominal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nominal Pengajuan
                </label>
                <input type="number" x-model="nominal"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Maksimal pengajuan 200.000.000">
                <p x-show="errors.nominal" class="text-red-500 text-sm mt-1">
                    <span x-text="errors.nominal"></span>
                </p>
            </div>

            <!-- Tenor -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tenor (9 bulan, 16 bulan, 24 bulan)
                </label>
                <select x-model="tenor"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Pilih tenor</option>
                    <option value="9">9 bulan</option>
                    <option value="16">16 bulan</option>
                    <option value="24">24 bulan</option>
                </select>
                <p x-show="errors.tenor" class="text-red-500 text-sm mt-1">
                    <span x-text="errors.tenor"></span>
                </p>
            </div>

            <!-- Pendapatan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pendapatan Bulanan
                </label>
                <input type="number" x-model="income"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Minimal pendapatan 1.000.000">
                <p x-show="errors.income" class="text-red-500 text-sm mt-1">
                    <span x-text="errors.income"></span>
                </p>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan
                </label>
                <textarea x-model="notes" rows="3"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Tambahkan catatan jika ada"></textarea>
            </div>

            <!-- Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Ajukan Sekarang
                </button>
            </div>

        </form>
    </div>
    <script>
        function applicationForm() {
            return {
                name: '',
                income: '',
                application_type: '',
                nominal: '',
                tenor: '',
                notes: '',

                errors: {},

                resetForm() {
                    this.name = '';
                    this.income = '';
                    this.application_type = '';
                    this.nominal = '';
                    this.tenor = '';
                    this.notes = '';
                },

                validate() {
                    this.errors = {};

                    if (!this.name.trim()) {
                        this.errors.name = 'Nama lengkap wajib diisi.';
                    }

                    if (!this.application_type) {
                        this.errors.application_type = 'Tipe pengajuan wajib dipilih.';
                    }

                    if (!this.nominal || this.nominal <= 0 ) {
                        this.errors.nominal = 'Nominal pengajuan harus lebih dari 0.';
                    }

                    if(this.nominal >= 200000000){
                        this.errors.nominal = 'Nominal pengajuan tidak boleh lebih dari 200.000.000.';
                    }

                    if (!this.tenor) {
                        this.errors.tenor = 'Tenor wajib dipilih.';
                    }

                    if (!this.income || this.income <= 0 || this.income < 1000000) {
                        this.errors.income = 'Pendapatan bulanan harus lebih dari 1.000.000.';
                    }

                    return Object.keys(this.errors).length === 0
                },

                submitForm() {
                    if (!this.validate()) {
                        return
                    }

                    let formData = new FormData();

                    formData.append('name', this.name)
                    formData.append('application_type', this.application_type)
                    formData.append('nominal', this.nominal)
                    formData.append('tenor', this.tenor)
                    formData.append('income', this.income)
                    formData.append('notes', this.notes)

                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }


                }
            }
        }
    </script>
</body>

</html>