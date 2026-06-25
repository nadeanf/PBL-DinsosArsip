<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import HeaderBaru from '@/components/header.vue';
import FooterBaru from '@/components/footer.vue';
import { ref, onMounted, computed } from 'vue';
import { FileText, FileImage, FileSpreadsheet, File } from 'lucide-vue-next'

const page = usePage();

const arsip = page.props.arsip || [];
const pengumuman = page.props.pengumuman || []
const totalArsip = page.props.totalArsip || 0;
const currentPage = ref(0);
const itemsPerPage = 3;
const openPreview = (doc) => {
  if (!doc) return

  const previewPath = getPreviewPath(doc)

  // ✅ CEK: ini arsip atau pengumuman
  if (doc.files) {
    // ===== ARSIP =====
    selectedDocType.value = 'arsip'

    selectedDoc.value = {
      ...doc,
      title: doc.judul,
      nomor: doc.nomor,
      kategori: doc.kategori?.nama || '-',
      jenis: getFileType(previewPath),
      tahun: new Date(doc.created_at).getFullYear(),
      lokasi: doc.user?.bagian || '-',
      format: getFileType(previewPath),
      previewPath,
    }

  } else {
    // ===== PENGUMUMAN =====
    selectedDocType.value = 'pengumuman'

    selectedDoc.value = {
      ...doc,
      format: getFileType(previewPath),
      previewPath,
    }
  }

  previewModal.value = true
}

const paginatedPengumuman = computed(() => {
    const start = currentPage.value * itemsPerPage;

    return pengumuman.slice(start, start + itemsPerPage);
});

const nextSlide = () => {
    if ((currentPage.value + 1) * itemsPerPage < pengumuman.length) {
        currentPage.value++;
    }
};

const prevSlide = () => {
    if (currentPage.value > 0) {
        currentPage.value--;
    }
};

const docCount = ref(0);

function animateCounter(target: number, duration: number) {
    const start = performance.now();

    const step = (timestamp: number) => {
        const progress = Math.min((timestamp - start) / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);

        docCount.value = Math.round(ease * target);

        if (progress < 1) {
            requestAnimationFrame(step);
        }
    };

    requestAnimationFrame(step);
}

onMounted(() => {
    setTimeout(() => animateCounter(totalArsip, 1800), 600);

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        },
        { threshold: 0.15 }
    );

    document
        .querySelectorAll('.fade-up, .fade-left, .fade-right')
        .forEach((el) => observer.observe(el));
});

/* PREVIEW MODAL */
const previewModal = ref(false)
const selectedDoc = ref(null)

const getFileType = (path) => {
  if (!path) return 'FILE'

  const ext = path.split('.').pop()?.toLowerCase()
  if (ext === 'pdf') return 'PDF'
  if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) return 'IMAGE'

  return 'FILE'
}

const getPreviewPath = (doc) => {
  if (!doc) return null
  if (doc.file) return doc.file
  return doc?.files?.[0]?.path_file || null
}

const selectedDocType = ref('')

const getFileIcon = (filePath) => {
  if (!filePath || typeof filePath !== 'string') return File

  const ext = filePath.split('.').pop()?.toLowerCase()

  if (!ext) return File

  if (ext === 'pdf') return FileText
  if (['doc', 'docx'].includes(ext)) return FileText
  if (['xls', 'xlsx', 'csv'].includes(ext)) return FileSpreadsheet
  if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) return FileImage

  return File
}

</script>

<template>
    <Head title="SIDARLING - Dinas Sosial Boyolali" />

    <div
        class="min-h-screen bg-white flex flex-col w-full overflow-x-hidden font-sans text-slate-800"
    >
        <HeaderBaru />

        <main class="flex-grow">
            <!-- HERO -->
            <div
                class="relative h-[480px] bg-cover bg-center flex items-center justify-center overflow-visible"
                style="background-image: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('/image/bgsosis.png');"
            >
                <h1
                    class="text-white leading-none mb-16 drop-shadow-[0_12px_15px_rgba(0,0,0,0.9)] tracking-[0.15em] animate-title-pop"
                    style="font-family: 'Georgia', serif; font-style: italic; font-weight: 400; font-size: clamp(64px, 12vw, 50px); -webkit-text-stroke: 1px rgba(255,255,255,0.25);"
                >
                    SIDARLING DINSOS
                </h1>

                <!-- STAT CARD -->
                <div
                    class="absolute -bottom-14 flex gap-5 px-4 w-full justify-center z-20 max-w-3xl left-1/2 -translate-x-1/2"
                >
                    <!-- TOTAL -->
                    <div
                        class="bg-white/28 backdrop-blur-2xl px-7 py-5 flex items-center gap-4 shadow-2xl border border-white/40 rounded-[22px] min-w-[210px] animate-stat-1"
                    >
                        <span
                            class="text-5xl font-black text-[#1e3a8a] leading-none"
                        >
                            {{ docCount }}
                        </span>

                        <div
                            class="text-left leading-tight uppercase font-bold text-slate-900"
                        >
                            <p class="text-sm">Total</p>
                            <p class="text-[10px] opacity-70">Dokumen</p>
                        </div>
                    </div>

                    <!-- PAPERLESS -->
                    <div
                        class="bg-white/28 backdrop-blur-2xl px-7 py-5 flex items-center gap-4 shadow-2xl border border-white/40 rounded-[22px] min-w-[210px] animate-stat-2"
                    >
                        <div
                            class="w-11 h-11 bg-[#1e3a8a]/15 rounded-xl flex-shrink-0 border border-white/30 flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 stroke-[#1e3a8a]"
                                fill="none"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"
                                />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                        </div>

                        <div
                            class="text-left leading-tight uppercase font-bold text-slate-900"
                        >
                            <p class="text-sm">Paperless</p>
                            <p class="text-[10px] opacity-70">
                                Arsip Digital
                            </p>
                        </div>
                    </div>

                    <!-- ONLINE -->
                    <div
                        class="bg-white/28 backdrop-blur-2xl px-7 py-5 flex items-center gap-4 shadow-2xl border border-white/40 rounded-[22px] min-w-[210px] animate-stat-3"
                    >
                        <div
                            class="w-11 h-11 bg-[#1e3a8a]/15 rounded-xl flex-shrink-0 border border-white/30 flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 stroke-[#1e3a8a]"
                                fill="none"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>

                        <div
                            class="text-left leading-tight uppercase font-bold text-slate-900"
                        >
                            <p class="text-sm">24/7</p>
                            <p class="text-[10px] opacity-70">
                                Akses Online
                            </p>
                        </div>
                    </div>
                </div>

                <!-- BLUR BOTTOM -->
<div
    class="absolute bottom-0 left-0 w-full h-28 pointer-events-none z-10"
>
    <div
        class="absolute inset-0 backdrop-blur-[2px]"
    ></div>

    <div
        class="absolute inset-0 bg-gradient-to-b from-transparent via-white/5 to-white"
    ></div>
</div>
</div>

            <!-- LIST ARSIP -->
            <div class="max-w-5xl mx-auto mt-36 px-6 mb-24">
                <div class="flex flex-col gap-5">
                    <div
                        v-for="(doc, i) in arsip"
                        :key="doc.id"
                        @click="openPreview(doc)"
                        :class="[
                            'doc-card',
                            i === 0
                                ? 'fade-left'
                                : i === 1
                                ? 'fade-up'
                                : 'fade-right'
                        ]"
                        :style="{ transitionDelay: `${i * 0.1}s` }"
                        class="cursor-pointer bg-[#8db1c9] p-7 rounded-[22px] flex items-center shadow-lg border border-white/40 relative w-full overflow-hidden transition-transform duration-250 hover:-translate-y-1 hover:shadow-xl"
                    >
                       <!-- ICON -->
                        <div
    class="bg-white rounded-xl shadow-inner flex-shrink-0 flex items-center justify-center mr-7"
    style="width:72px;height:72px;"
>
    <component
        :is="getFileIcon(getPreviewPath(doc) || '')"
        class="w-8 h-8 text-gray-500"
    />
</div>
                        <!-- CONTENT -->
                        <div class="flex-grow text-white">
                            <h4
                                class="font-bold text-xl mb-3 tracking-tight"
                            >
                                {{ doc.judul }}
                            </h4>

                            <div
                                class="flex flex-wrap items-center gap-x-10 gap-y-1 text-[11px] font-bold uppercase tracking-wider opacity-90"
                            >
                                <span>
                                    No : {{ doc.nomor || '-' }}
                                </span>

                                <span>
                                    Kategori :
                                    {{ doc.kategori?.nama || '-' }}
                                </span>

                                <span>
                                    Divisi :
                                    {{ doc.user?.bagian || '-' }}
                                </span>

                                <div class="w-full h-0"></div>

                                <span>
                                    Ukuran :
                                    {{
                                        doc.files?.[0]?.size
                                            ? (
                                                  doc.files[0].size /
                                                  1024 /
                                                  1024
                                              ).toFixed(1) + ' MB'
                                            : '-'
                                    }}
                                </span>

                                <span>
                                    Tanggal :
                                    {{
                                        new Date(
                                            doc.created_at
                                        ).toLocaleDateString('id-ID')
                                    }}
                                </span>
                            </div>
                        </div>

                        <!-- STATUS -->
                        <div class="flex-shrink-0 ml-4 self-start">
                            <span
                                class="bg-white px-5 py-1.5 rounded-full text-[10px] font-black text-slate-500 uppercase shadow-sm"
                            >
                                {{ doc.status_akses || 'Public' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PENGUMUMAN -->
                <div class="max-w-6xl mx-auto px-6 mb-24 mt-24">

                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-black text-slate-800">
                            Pengumuman
                        </h2>
                    </div>

                    <!-- GRID -->
                    <div class="relative">

                        <!-- LEFT -->
                        <button
                            v-if="currentPage > 0"
                            @click="prevSlide"
                            class="absolute -left-5 top-1/2 -translate-y-1/2 z-20 bg-white border-2 border-black shadow-lg w-11 h-11 rounded-lg text-xl font-bold hover:bg-slate-100 transition"
                        >
                            <
                        </button>

                        <!-- RIGHT -->
                        <button
                            v-if="(currentPage + 1) * itemsPerPage < pengumuman.length"
                            @click="nextSlide"
                            class="absolute -right-5 top-1/2 -translate-y-1/2 z-20 bg-white border-2 border-black shadow-lg w-11 h-11 rounded-lg text-xl font-bold hover:bg-slate-100 transition"
                        >
                            >
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div
                                v-for="item in paginatedPengumuman"
                                :key="item.id"
                                @click="openPreview(item)"
                                class="bg-white rounded-[24px] overflow-hidden shadow-lg border border-slate-200 hover:-translate-y-1 hover:shadow-2xl transition duration-300"
                            >

                                <!-- IMAGE/PDF PREVIEW -->
                                <div class="h-48 overflow-hidden bg-slate-100 flex items-center justify-center">
                                    <img
                                        v-if="item.file && getFileType(item.file) === 'IMAGE'"
                                        :src="`/storage/${item.file}`"
                                        alt="pengumuman"
                                        class="w-full h-full object-cover"
                                    >

                                    <iframe
                                        v-else-if="item.file && getFileType(item.file) === 'PDF'"
                                        :src="`/storage/${item.file}`"
                                        class="w-full h-full"
                                    ></iframe>

                                    <div
                                        v-else
                                        class="w-full h-full flex items-center justify-center text-slate-400 text-sm font-bold"
                                    >
                                        No Image
                                    </div>
                                </div>

                                <!-- CONTENT -->
                                <div class="p-5">

                                    <!-- DATE -->
                                    <div class="mb-3">
                                        <span class="bg-[#8db1c9]/15 text-[#5b87a3] text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full">
                                            {{
                                                new Date(item.tanggal).toLocaleDateString('id-ID', {
                                                    day: 'numeric',
                                                    month: 'long',
                                                    year: 'numeric'
                                                })
                                            }}
                                        </span>
                                    </div>

                                    <!-- TITLE -->
                                    <h3 class="text-slate-800 font-black text-lg leading-snug mb-3 line-clamp-2">
                                        {{ item.judul }}
                                    </h3>

                                    <!-- DESC -->
                                    <p class="text-slate-500 text-sm leading-relaxed line-clamp-3">
                                        {{ item.deskripsi }}
                                    </p>

                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- EMPTY -->
                    <div
                        v-if="pengumuman.length === 0"
                        class="text-center py-16 text-slate-400 font-semibold"
                    >
                        Belum ada pengumuman
                    </div>

                </div>
                </div>

                <!-- PREVIEW MODAL -->
                <div
                    v-if="previewModal && selectedDoc"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6"
                    @click.self="previewModal = false"
                >
                    <div
                        class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row"
                    >

                        <template v-if="selectedDocType === 'pengumuman'">
                            <!-- ANNOUNCEMENT PREVIEW (unchanged) -->
                            <div
                                class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6"
                            >

                                <img
                                    v-if="selectedDoc.file && getFileType(selectedDoc.file) === 'IMAGE'"
                                    :src="`/storage/${selectedDoc.file}`"
                                    class="max-h-[400px] object-contain rounded-xl shadow"
                                />

                                <iframe
                                    v-else-if="selectedDoc.file && getFileType(selectedDoc.file) === 'PDF'"
                                    :src="`/storage/${selectedDoc.file}`"
                                    class="w-full h-[400px] rounded-xl"
                                />

                                <div
                                    v-else
                                    class="text-slate-400 font-bold text-center"
                                >
                                    No preview tersedia
                                </div>

                            </div>

                            <div class="w-full md:w-1/2 p-8 flex flex-col">

                                <div class="flex items-start justify-between mb-5">

                                    <span
                                        class="bg-[#8db1c9]/15 text-[#5b87a3] text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full"
                                    >
                                        {{
                                            new Date(selectedDoc.tanggal || selectedDoc.created_at).toLocaleDateString('id-ID', {
                                                day: 'numeric',
                                                month: 'long',
                                                year: 'numeric'
                                            })
                                        }}
                                    </span>

                                    <button
                                        @click="previewModal = false"
                                        class="text-2xl font-black text-slate-400 hover:text-black"
                                    >
                                        ×
                                    </button>

                                </div>

                                <h2 class="text-3xl font-black text-slate-800 mb-4 leading-tight">
                                    {{ selectedDoc.judul }}
                                </h2>

                                <p class="text-slate-600 leading-relaxed overflow-y-auto">
                                    {{ selectedDoc.deskripsi }}
                                </p>
                            </div>
                        </template>
<template v-else>
  <!-- LEFT -->
  <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">

    <img
      v-if="selectedDoc.format === 'IMAGE'"
      :src="`/storage/${selectedDoc.previewPath}`"
      class="max-h-[400px] object-contain rounded-xl shadow"
    />

    <iframe
      v-else-if="selectedDoc.format === 'PDF'"
      :src="`/storage/${selectedDoc.previewPath}`"
      class="w-full h-[400px] rounded-xl"
    ></iframe>

    <div v-else class="text-gray-500 text-center">
      📄<br/>Preview tidak tersedia
    </div>

  </div>

  <!-- RIGHT -->
  <div class="w-full md:w-1/2 p-8 flex flex-col justify-between">

    <div>
      <div class="flex justify-between items-start mb-4">
        <h2 class="text-2xl font-black text-gray-800">
          {{ selectedDoc.title }}
        </h2>

        <button @click="previewModal=false">✕</button>
      </div>

      <!-- TAG -->
      <div class="flex flex-wrap gap-2 mb-4">
        <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-bold">
          No: {{ selectedDoc.nomor }}
        </span>

        <span class="bg-blue-100 px-3 py-1 rounded-full text-xs font-bold">
          {{ selectedDoc.kategori }}
        </span>

        <span class="bg-green-100 px-3 py-1 rounded-full text-xs font-bold uppercase">
          {{ selectedDoc.jenis }}
        </span>
      </div>

      <!-- GRID -->
      <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
          <p class="font-bold">Tahun</p>
          <p>{{ selectedDoc.tahun }}</p>
        </div>

        <div>
          <p class="font-bold">Status</p>
          <p>Public</p>
        </div>

        <div class="col-span-2">
          <p class="font-bold">Lokasi</p>
          <p>{{ selectedDoc.lokasi }}</p>
        </div>
      </div>

      <!-- DESC -->
      <div class="mt-6">
        <p class="font-bold">Deskripsi</p>
        <p>{{ selectedDoc.deskripsi || '-' }}</p>
      </div>
    </div>

    <!-- ACTION -->
    <div class="flex justify-end gap-3 mt-6">

      <button
        v-if="selectedDoc.files?.length"
        @click="handleDownload(selectedDoc.id)"
        class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold"
      >
        Download
      </button>

      <button
        @click="previewModal=false; selectedDoc = null"
        class="bg-slate-100 px-4 py-2 rounded-xl text-sm"
      >
        Tutup
      </button>

    </div>

  </div>
</template>
                    </div>
                </div>
            </main>
        <FooterBaru />
    </div>
</template>

<style>
@keyframes titlePop {
    from {
        opacity: 0;
        transform: scale(0.85) translateY(20px);
    }

    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes statSlide {
    from {
        opacity: 0;
        transform: translateY(16px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-title-pop {
    animation: titlePop 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.animate-stat-1 {
    opacity: 0;
    animation: statSlide 0.6s ease 0.5s forwards;
}

.animate-stat-2 {
    opacity: 0;
    animation: statSlide 0.6s ease 0.7s forwards;
}

.animate-stat-3 {
    opacity: 0;
    animation: statSlide 0.6s ease 0.9s forwards;
}

.animate-fade-in {
    animation: fade-in 0.2s ease-out;
}

.fade-up {
    opacity: 0;
    transform: translateY(32px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.fade-left {
    opacity: 0;
    transform: translateX(-32px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.fade-right {
    opacity: 0;
    transform: translateX(32px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.fade-up.visible,
.fade-left.visible,
.fade-right.visible {
    opacity: 1;
    transform: translate(0, 0);
}
</style>