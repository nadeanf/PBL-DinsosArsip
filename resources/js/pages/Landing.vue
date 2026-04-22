<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import HeaderBaru from '@/components/header.vue';
import FooterBaru from '@/components/footer.vue';
import { Search, Filter } from 'lucide-vue-next';
import { ref, onMounted } from 'vue';

const showFilter = ref(false);
const docCount = ref(0);

function animateCounter(target: number, duration: number) {
    const start = performance.now();
    const step = (timestamp: number) => {
        const progress = Math.min((timestamp - start) / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);
        docCount.value = Math.round(ease * target);
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}

onMounted(() => {
    setTimeout(() => animateCounter(20, 1800), 600);

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.fade-up, .fade-left, .fade-right').forEach(el => observer.observe(el));
});
</script>

<template>
    <Head title="SOSISBOY - Dinas Sosial Boyolali" />

    <div class="min-h-screen bg-white flex flex-col w-full overflow-x-hidden font-sans text-slate-800">

        <HeaderBaru />

        <main class="flex-grow">

            <div class="relative h-[480px] bg-cover bg-center flex items-center justify-center overflow-visible"
                 style="background-image: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('/image/bgsosis.png');">

                <h1 class="text-white leading-none mb-16 drop-shadow-[0_12px_15px_rgba(0,0,0,0.9)] tracking-[0.15em] animate-title-pop"
                    style="font-family: 'Georgia', serif; font-style: italic; font-weight: 900; font-size: clamp(64px, 12vw, 120px); -webkit-text-stroke: 1px rgba(255,255,255,0.25);">
                    SOSISBOY
                </h1>


                <div class="absolute -bottom-14 flex gap-5 px-4 w-full justify-center z-20 max-w-3xl left-1/2 -translate-x-1/2">


                    <div class="bg-white/28 backdrop-blur-2xl px-7 py-5 flex items-center gap-4 shadow-2xl border border-white/40 rounded-[22px] min-w-[210px] animate-stat-1">
                        <span class="text-5xl font-black text-[#1e3a8a] leading-none">{{ docCount }}</span>
                        <div class="text-left leading-tight uppercase font-bold text-slate-900">
                            <p class="text-sm">Total</p>
                            <p class="text-[10px] opacity-70">Dokumen</p>
                        </div>
                    </div>


                    <div class="bg-white/28 backdrop-blur-2xl px-7 py-5 flex items-center gap-4 shadow-2xl border border-white/40 rounded-[22px] min-w-[210px] animate-stat-2">
                        <div class="w-11 h-11 bg-[#1e3a8a]/15 rounded-xl flex-shrink-0 border border-white/30 flex items-center justify-center">
                            <svg class="w-5 h-5 stroke-[#1e3a8a]" fill="none" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <div class="text-left leading-tight uppercase font-bold text-slate-900">
                            <p class="text-sm">Paperless</p>
                            <p class="text-[10px] opacity-70">Arsip Digital</p>
                        </div>
                    </div>


                    <div class="bg-white/28 backdrop-blur-2xl px-7 py-5 flex items-center gap-4 shadow-2xl border border-white/40 rounded-[22px] min-w-[210px] animate-stat-3">
                        <div class="w-11 h-11 bg-[#1e3a8a]/15 rounded-xl flex-shrink-0 border border-white/30 flex items-center justify-center">
                            <svg class="w-5 h-5 stroke-[#1e3a8a]" fill="none" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div class="text-left leading-tight uppercase font-bold text-slate-900">
                            <p class="text-sm">24/7</p>
                            <p class="text-[10px] opacity-70">Akses Online</p>
                        </div>
                    </div>
                </div>
                    <div class="absolute bottom-0 left-0 w-full h-28 pointer-events-none z-10">
                    

                    <div class="absolute inset-0 backdrop-blur-[2px]"></div>
                    

                    <div class="absolute inset-0 bg-gradient-to-b 
                                from-transparent 
                                via-white/5
                                to-white">
                    </div>

                </div>

            </div>


            <div class="max-w-5xl mx-auto mt-36 px-6 mb-24">

                <!-- SEARCH BAR -->
                <div class="fade-up bg-[#5a86bc] p-3 rounded-2xl flex gap-3 shadow-xl mb-8 border border-white/20">
                    <div class="relative flex-grow">
                        <Search class="absolute left-4 top-3.5 text-slate-200 w-5 h-5" />
                        <input type="text" placeholder="Cari dokumen, nomor surat, atau kata kunci..."
                               class="w-full pl-12 pr-5 py-3 rounded-xl border-none focus:ring-0 text-sm bg-white/20 text-white placeholder:text-slate-200">
                    </div>

                    <select class="px-4 py-3 rounded-xl border-none bg-white/20 text-white text-sm focus:ring-0 cursor-pointer">
                        <option class="text-slate-800">Semua Kategori</option>
                        <option class="text-slate-800">Aktif</option>
                        <option class="text-slate-800">Inaktif</option>
                        <option class="text-slate-800">Vital</option>
                    </select>

                    <input type="date" class="px-4 py-3 rounded-xl border-none bg-white/20 text-white/80 text-sm focus:ring-0 cursor-pointer" />
                    <input type="date" class="px-4 py-3 rounded-xl border-none bg-white/20 text-white/80 text-sm focus:ring-0 cursor-pointer" />

                    <button class="bg-white px-7 py-3 rounded-xl font-bold text-[#2c52a7] hover:bg-slate-50 transition shadow-md text-sm">
                        Cari
                    </button>
                </div>

                <!-- FILTER -->
                <div v-if="showFilter"
                     class="bg-white mb-8 p-6 rounded-2xl shadow-xl border border-slate-200 animate-fade-in">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex flex-col w-full">
                            <label class="text-xs font-bold mb-1 text-slate-600">Tanggal</label>
                            <input type="date" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#2c52a7]">
                        </div>
                        <div class="flex flex-col w-full">
                            <label class="text-xs font-bold mb-1 text-slate-600">Kategori</label>
                            <select class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#2c52a7]">
                                <option>Semua</option>
                                <option>Aktif</option>
                                <option>Inaktif</option>
                                <option>Vital</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- LIST ARSIP -->
                <div class="flex flex-col gap-5">
                    <div v-for="(doc, i) in 3" :key="i"
                         :class="['doc-card', i === 0 ? 'fade-left' : i === 1 ? 'fade-up' : 'fade-right']"
                         :style="{ transitionDelay: `${i * 0.1}s` }"
                         class="bg-[#8db1c9] p-7 rounded-[22px] flex items-center shadow-lg border border-white/40 relative w-full overflow-hidden transition-transform duration-250 hover:-translate-y-1 hover:shadow-xl">

                        <div class="w-18 h-18 bg-white rounded-xl shadow-inner flex-shrink-0 flex items-center justify-center mr-7" style="width:72px;height:72px;">
                            <span class="text-slate-300 font-black text-[10px] uppercase">DOCS</span>
                        </div>

                        <div class="flex-grow text-white">
                            <h4 class="font-bold text-xl mb-3 tracking-tight">Proposal Program Pemberdayaan Masyarakat</h4>
                            <div class="flex flex-wrap items-center gap-x-10 gap-y-1 text-[11px] font-bold uppercase tracking-wider opacity-90">
                                <span>No : DOK/012/11/2026</span>
                                <span>Kategori : Proposal</span>
                                <span>Divisi : Sekretariat</span>
                                <div class="w-full h-0"></div>
                                <span>Ukuran : 3,5 MB</span>
                                <span>Tanggal : 25 Januari 2026</span>
                            </div>
                        </div>

                        <div class="flex-shrink-0 ml-4 self-start">
                            <span class="bg-white px-5 py-1.5 rounded-full text-[10px] font-black text-slate-500 uppercase shadow-sm">Public</span>
                        </div>
                    </div>
                </div>

                <!-- PAGINATION -->
                <div class="fade-up flex justify-center mt-16 gap-2" style="transition-delay:0.3s">
                    <button v-for="p in ['«', '‹', '1', '2', '...', '4', '›', '»']" :key="p"
                            :class="['w-10 h-10 flex items-center justify-center rounded-lg border text-[11px] font-bold transition shadow-sm',
                                     p === '1' ? 'bg-[#2c52a7] text-white border-[#2c52a7]' : 'bg-white border-slate-200 text-slate-500 hover:bg-[#2c52a7] hover:text-white hover:border-[#2c52a7]']">
                        {{ p }}
                    </button>
                </div>
            </div>
        </main>

        <FooterBaru />
    </div>
</template>

<style>
@keyframes titlePop {
    from { opacity: 0; transform: scale(0.85) translateY(20px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
@keyframes statSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}

.animate-title-pop { animation: titlePop 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
.animate-stat-1    { opacity: 0; animation: statSlide 0.6s ease 0.5s forwards; }
.animate-stat-2    { opacity: 0; animation: statSlide 0.6s ease 0.7s forwards; }
.animate-stat-3    { opacity: 0; animation: statSlide 0.6s ease 0.9s forwards; }
.animate-fade-in   { animation: fade-in 0.2s ease-out; }

.fade-up    { opacity: 0; transform: translateY(32px);  transition: opacity 0.6s ease, transform 0.6s ease; }
.fade-left  { opacity: 0; transform: translateX(-32px); transition: opacity 0.6s ease, transform 0.6s ease; }
.fade-right { opacity: 0; transform: translateX(32px);  transition: opacity 0.6s ease, transform 0.6s ease; }
.fade-up.visible, .fade-left.visible, .fade-right.visible { opacity: 1; transform: translate(0, 0); }
</style>