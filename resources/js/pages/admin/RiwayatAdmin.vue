<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed, watch, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const previewModal = ref(false)
const selectedDoc = ref(null)

defineOptions({ layout: AdminLayout })

// ambil data dari controller
const props = defineProps({
    riwayat: Array
})

// state
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 5

const filteredHistory = computed(() => {
    return mappedHistory.value.filter(item =>
        String(item.title || '')
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase()) ||

        String(item.aksi || '')
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase())
    )
})

watch(searchQuery, () => {
    currentPage.value = 1
})

// pagination
const totalPages = computed(() =>
    Math.ceil(filteredHistory.value.length / itemsPerPage)
)

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return filteredHistory.value.slice(start, start + itemsPerPage)
})

const setPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}

// format tanggal
const formatTanggal = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    })
}

const formatWaktu = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return 'pukul ' + date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    }).replace('.', ':')
}

const openPreview = (item) => {
    // buka modal dulu, lalu set selectedDoc
    previewModal.value = true
    
    // gunakan nextTick untuk memastikan modal sudah render
    nextTick(() => {
        selectedDoc.value = item
    })

    // catat lihat
    fetch('/riwayat/view', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        },
        body: JSON.stringify({
            dokumen_id: item.id
        })
    })
}

const downloadFile = (item) => {
    window.location.href = `/download/${item.id}`
}

const normalizeText = (value) =>
    String(value || '').toLowerCase().trim()

const canAccessFull = (doc) => {
    const user = page.props.auth?.user

    if (!doc || !user) return false

    // ADMIN & SUPERADMIN
    if (['admin', 'superadmin'].includes(user.role)) {
        return true
    }

    // PUBLIK
    if (normalizeText(doc.status) === 'publik') {
        return true
    }

    // OWNER
    if (doc.user_id === user.id) {
        return true
    }

    // APPROVED
    if (doc.request_status === 'approved') {
        return true
    }

    // BIDANG SAMA
    if (
        normalizeText(doc.status) === 'private' &&
        normalizeText(doc.bidang) === normalizeText(user.bagian)
    ) {
        return true
    }

    return false
}

const requestAkses = (arsipId) => {
    fetch(`/request-akses/${arsipId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        }
    }).then(() => {
        selectedDoc.value.request_status = 'pending'
    })
}

const getFileType = (path) => {
    if (!path) return 'FILE'

    const ext = path.split('.').pop()?.toLowerCase()

    if (['jpg','jpeg','png','gif','webp'].includes(ext)) return 'IMAGE'
    if (ext === 'pdf') return 'PDF'
    if (['xls', 'xlsx', 'csv'].includes(ext)) return 'EXCEL'

    return 'FILE'
}

const mappedHistory = computed(() => {
    return (props.riwayat || []).map(item => ({
        ...item,
        format: item.files?.length
            ? getFileType(item.files?.[0]?.path_file)
            : 'FILE'
    }))
})
</script>

<template>
    <Head title="Riwayat - Admin" />

    <div class="py-10 px-6 max-w-7xl mx-auto">

        <h1 class="text-4xl font-black mb-10 text-gray-800">
            Riwayat Aktivitas
        </h1>

        <input 
            v-model="searchQuery"
            type="text"
            placeholder="Cari dalam riwayat..."
            class="w-full p-4 border rounded-xl mb-6"
        />

        <div 
            v-for="item in paginatedData"
            :key="item.id"
            @click="openPreview(item)"
            class="cursor-pointer bg-[#7fa1b1] p-5 rounded-xl mb-4 text-white shadow-md transition hover:bg-[#6c8f9f]"
        >
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-bold text-lg">
                        {{ item.title }}
                    </p>
                </div>

                <div class="text-right flex flex-col items-end gap-1">
                    <span class="text-xs font-semibold bg-white/20 px-2 py-0.5 rounded">
                        Status: {{ item.aksi || 'Lihat' }}
                    </span>
                    <span class="text-xs font-medium mt-1">
                        {{ formatTanggal(item.waktu) }}
                    </span>
                    <span class="text-[10px] opacity-80">
                        {{ formatWaktu(item.waktu) }}
                    </span>
                </div>
            </div>
        </div>

        <div
            v-if="totalPages > 1"
            class="flex justify-center items-center gap-2 mt-6"
        >
            <button
                @click="setPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-1 rounded-lg bg-gray-200 disabled:opacity-50"
            >
                Prev
            </button>

            <button
                v-for="p in totalPages"
                :key="p"
                @click="setPage(p)"
                :class="[
                    'px-3 py-1 rounded-lg',
                    currentPage === p
                        ? 'bg-blue-600 text-white'
                        : 'bg-gray-200'
                ]"
            >
                {{ p }}
            </button>

            <button
                @click="setPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 rounded-lg bg-gray-200 disabled:opacity-50"
            >
                Next
            </button>
        </div>

        <div
            v-if="filteredHistory.length === 0"
            class="text-center py-10 text-gray-400"
        >
            Data tidak ada
        </div>

    </div>

    <div
        v-if="previewModal && selectedDoc"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6"
        @click.self="previewModal = false"
    >
        <div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row">
            
            <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">
                <img 
                    v-if="selectedDoc?.format === 'IMAGE' && canAccessFull(selectedDoc)"
                    :src="`/storage/${selectedDoc?.files?.[0]?.path_file}`"
                    class="max-h-[400px] object-contain rounded-xl shadow"
                />

                <iframe 
                    v-else-if="selectedDoc?.format === 'PDF' && canAccessFull(selectedDoc)"
                    :src="`/storage/${selectedDoc?.files?.[0]?.path_file}`"
                    class="w-full h-[400px] rounded-xl"
                >
                </iframe>

                <div
                    v-else-if="canAccessFull(selectedDoc)"
                    class="text-center space-y-4"
                >
                    <div class="text-6xl">📄</div>
                    <p class="font-semibold text-gray-700">
                        Preview tidak tersedia
                    </p>
                </div>

                <div
                    v-else
                    class="text-gray-500 text-center space-y-3"
                >
                    <div>
                        🔒 Dokumen ini bersifat privat <br />
                        Anda tidak memiliki akses
                    </div>

                    <div
                        v-if="selectedDoc?.request_status === 'pending'"
                        class="text-yellow-500 font-semibold text-sm"
                    >
                        ⏳ Menunggu persetujuan
                    </div>

                    <div
                        v-else-if="selectedDoc?.request_status === 'ditolak'"
                        class="text-red-500 font-semibold text-sm"
                    >
                        ❌ Akses ditolak
                    </div>

                    <button
                        v-else
                        @click.stop.prevent="requestAkses(selectedDoc.id)"
                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold"
                    >
                        Minta Akses
                    </button>
                </div>
            </div>

            <div class="w-full md:w-1/2 p-8 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-black text-gray-800">
                            {{ selectedDoc?.title }}
                        </h2>
                        <button @click="previewModal = false" class="text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-bold">
                            No: {{ selectedDoc?.nomor }}
                        </span>
                        <span class="bg-blue-100 px-3 py-1 rounded-full text-xs font-bold">
                            {{ selectedDoc?.kategori }}
                        </span>
                        <span class="bg-green-100 px-3 py-1 rounded-full text-xs font-bold uppercase">
                            {{ selectedDoc?.jenis }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="font-bold">Tahun</p>
                            <p>{{ selectedDoc?.tahun }}</p>
                        </div>
                        <div>
                            <p class="font-bold">Status</p>
                            <p>{{ selectedDoc?.status }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="font-bold">Lokasi</p>
                            <p>{{ selectedDoc?.lokasi }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="font-bold">Deskripsi</p>
                        <p class="text-gray-600">
                            {{ selectedDoc?.deskripsi || '-' }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a
                        v-if="selectedDoc?.files?.length && canAccessFull(selectedDoc)"
                        :href="`/download/${selectedDoc?.id}`"
                        class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold text-center hover:bg-blue-700"
                    >
                        Download
                    </a>
                    <button
                        @click="previewModal = false"
                        class="bg-gray-300 px-4 py-2 rounded-xl font-bold hover:bg-gray-400"
                    >
                        Tutup
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>