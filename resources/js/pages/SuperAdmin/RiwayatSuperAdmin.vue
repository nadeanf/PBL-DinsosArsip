<script setup>
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'

const page = usePage()

const previewModal = ref(false)
const selectedDoc = ref(null)

defineOptions({
    layout: SuperAdminLayout
})

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
        item.title?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        item.aksi?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        item.nama_user?.toLowerCase().includes(searchQuery.value.toLowerCase())
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

const setPage = (pageNum) => {
    if (pageNum >= 1 && pageNum <= totalPages.value) {
        currentPage.value = pageNum
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}

// format tanggal
const formatTanggal = (dateString) => {
    if (!dateString) return ''

    const date = new Date(dateString)

    return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const handleDownload = (docId) => {
    // Download file via GET request yang akan memanggil controller download method
    window.location.href = `/download/${docId}`
}

const openPreview = (item) => {
    selectedDoc.value = item
    previewModal.value = true
    
    // Track preview view dari halaman riwayat
    fetch('/riwayat/view', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ dokumen_id: item.id })
    }).catch(err => console.debug('Preview tracking:', err))
}

const getFileType = (path) => {
    if (!path) return 'FILE'

    const ext = path.split('.').pop()?.toLowerCase()

    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
        return 'IMAGE'
    }

    if (ext === 'pdf') {
        return 'PDF'
    }

    return 'FILE'
}

const mappedHistory = computed(() => {
    return (props.riwayat || []).map(item => ({
        ...item,
        format: item.files?.length
            ? getFileType(item.files[0].path_file)
            : 'FILE'
    }))
})
</script>

<template>
    <Head title="Riwayat Super Admin" />

    <div class="py-10 px-6 max-w-7xl mx-auto">

        <h1 class="text-4xl font-black mb-10 text-gray-800">
            Riwayat Aktivitas 
        </h1>

        <!-- SEARCH -->
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari aktivitas..."
            class="w-full p-4 border rounded-xl mb-6"
        />

        <!-- LIST -->
        <div
            v-for="item in paginatedData"
            :key="item.id"
            @click="openPreview(item)"
            class="cursor-pointer bg-[#7fa1b1] p-5 rounded-xl mb-4 text-white shadow-md"
        >

            <div class="flex justify-between">

                <div>
                    <p class="font-bold text-lg">
                        {{ item.title }}
                    </p>

                    <p class="text-sm capitalize">
                        {{ item.aksi }}
                    </p>

                    <p class="text-xs mt-1 opacity-80">
                        {{ item.nama_user }} • {{ item.role }}
                    </p>
                </div>

                <p class="text-xs bg-white/20 px-2 py-1 rounded">
                    {{ formatTanggal(item.waktu) }}
                </p>

            </div>

        </div>

        <!-- PAGINATION -->
        <div
            v-if="totalPages > 1"
            class="flex justify-center items-center gap-2 mt-6"
        >

            <!-- PREV -->
            <button
                @click="setPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-1 rounded-lg bg-gray-200 disabled:opacity-50"
            >
                Prev
            </button>

            <!-- ANGKA -->
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

            <!-- NEXT -->
            <button
                @click="setPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 rounded-lg bg-gray-200 disabled:opacity-50"
            >
                Next
            </button>

        </div>

        <!-- EMPTY -->
        <div
            v-if="filteredHistory.length === 0"
            class="text-center py-10 text-gray-400"
        >
            Belum ada aktivitas
        </div>

    </div>

    <!-- PREVIEW MODAL -->
    <div
        v-if="previewModal && selectedDoc"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6"
        @click.self="previewModal = false"
    >

        <div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row">

            <!-- PREVIEW -->
            <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">

                <!-- IMAGE -->
                <img
                    v-if="selectedDoc?.format === 'IMAGE'"
                    :src="`/storage/${selectedDoc?.files?.[0]?.path_file}`"
                    class="max-h-[400px] object-contain rounded-xl shadow"
                />

                <!-- PDF -->
                <iframe
                    v-else-if="selectedDoc?.format === 'PDF'"
                    :src="`/storage/${selectedDoc?.files?.[0]?.path_file}`"
                    class="w-full h-[400px] rounded-xl"
                ></iframe>

                <!-- FILE -->
                <div
                    v-else
                    class="text-gray-500 text-center"
                >
                    Preview tidak tersedia
                </div>

            </div>

            <!-- DETAIL -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-between">

                <div>

                    <div class="flex justify-between items-start mb-4">

                        <h2 class="text-2xl font-black text-gray-800">
                            {{ selectedDoc?.title }}
                        </h2>

                        <button @click="previewModal = false">
                            ✕
                        </button>

                    </div>

                    <!-- USER INFO -->
                    <div class="mb-4">

                        <p class="text-sm text-gray-500">
                            Diakses oleh:
                        </p>

                        <p class="font-bold text-gray-700">
                            {{ selectedDoc?.nama_user }}
                        </p>

                        <p class="text-xs uppercase text-gray-500">
                            {{ selectedDoc?.role }}
                        </p>

                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">

                        <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-bold">
                            No: {{ selectedDoc?.nomor }}
                        </span>

                        <span class="bg-blue-100 px-3 py-1 rounded-full text-xs font-bold">
                            {{ selectedDoc?.kategori }}
                        </span>

                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">

                        <div>
                            <p class="font-bold">
                                Aksi
                            </p>

                            <p class="capitalize">
                                {{ selectedDoc?.aksi }}
                            </p>
                        </div>

                        <div>
                            <p class="font-bold">
                                Waktu
                            </p>

                            <p>
                                {{ formatTanggal(selectedDoc?.waktu) }}
                            </p>
                        </div>

                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">

                    <button
                        v-if="selectedDoc?.files?.length"
                        @click="handleDownload(selectedDoc.id)"
                        class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold hover:bg-blue-700 transition"
                    >
                        Download
                    </button>

                    <button
                        @click="previewModal = false"
                        class="bg-gray-300 px-4 py-2 rounded-xl font-bold"
                    >
                        Tutup
                    </button>

                </div>

            </div>

        </div>

    </div>
</template>