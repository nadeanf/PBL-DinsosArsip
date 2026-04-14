<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import HeaderBaru from '@/components/header.vue';
import FooterBaru from '@/components/footer.vue';
import { Search, Filter, FileText, Clock } from 'lucide-vue-next';
</script>

<template>
    <Head title="SOSISBOY - Dinas Sosial Boyolali" />

    <div class="min-h-screen bg-white flex flex-col w-full overflow-x-hidden font-sans text-slate-800">
        
        <HeaderBaru />

        <main class="flex-grow">
            <div class="relative h-[530px] bg-cover bg-center flex items-center justify-center" 
                 style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('/image/bgsosis.png');">
                
                <h1 class="text-white text-7xl md:text-[10rem] leading-none mb-20 drop-shadow-[0_12px_15px_rgba(0,0,0,0.9)] tracking-[0.15em]" 
                    style="font-family: 'Arvo', serif; font-style: italic !important; font-weight: 700; -webkit-text-stroke: 1px rgba(255,255,255,0.3);">
                    SOSISBOY
                </h1>

                <div class="absolute -bottom-14 flex gap-6 px-4 w-full justify-center z-20">
                    <div v-for="stat in [{v:'20', t1:'Total', t2:'Dokumen'}, {v:'icon', t1:'Paperless', t2:'Arsip Digital'}, {v:'icon', t1:'24/7', t2:'Akses Online'}]" 
                         :key="stat.t1"
                         class="bg-white/30 backdrop-blur-2xl px-8 py-6 flex items-center gap-4 shadow-2xl border border-white/40 rounded-[25px] min-w-[240px]">
                        <span v-if="stat.v === '20'" class="text-5xl font-black text-[#1e3a8a] leading-none">20</span>
                        <div v-else class="w-12 h-12 bg-[#1e3a8a]/20 rounded-lg flex-shrink-0 border border-white/30 flex items-center justify-center">
                            <FileText v-if="stat.t1 === 'Paperless'" class="w-6 h-6 text-[#1e3a8a]" />
                            <Clock v-else class="w-6 h-6 text-[#1e3a8a]" />
                        </div>
                        <div class="text-left leading-tight uppercase font-bold text-slate-900">
                            <p class="text-sm">{{ stat.t1 }}</p>
                            <p class="text-[10px] opacity-70">{{ stat.t2 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-6xl mx-auto mt-44 px-6 mb-24">
                
                <div class="bg-[#5a86bc] p-3 rounded-2xl flex gap-3 shadow-xl mb-14 border border-white/20">
                    <div class="relative flex-grow">
                        <Search class="absolute left-4 top-3 text-slate-200 w-5 h-5" />
                        <input type="text" placeholder="Cari dokumen, nomor surat, atau kata kunci..." 
                               class="w-full pl-12 pr-5 py-3 rounded-xl border-none focus:ring-0 text-sm bg-white/20 text-white placeholder:text-slate-200 shadow-inner">
                    </div>
                    <button class="bg-white px-8 py-3 rounded-xl font-bold text-[#2c52a7] flex items-center gap-2 hover:bg-slate-50 transition shadow-md">
                        <Filter class="w-4 h-4" /> Filter
                    </button>
                </div>

                <div class="flex flex-col gap-6">
                    <div v-for="i in 3" :key="i" 
                         class="bg-[#8db1c9] p-8 rounded-[25px] flex items-center shadow-lg border border-white/40 relative w-full overflow-hidden">
                        
                        <div class="w-20 h-20 bg-white rounded-xl shadow-inner flex-shrink-0 flex items-center justify-center mr-8">
                             <span class="text-slate-300 font-black text-xs uppercase">DOCS</span>
                        </div>

                        <div class="flex-grow text-white">
                            <h4 class="font-bold text-2xl mb-3 tracking-tight">Proposal Program Pemberdayaan Masyarakat</h4>
                            
                            <div class="flex flex-wrap items-center gap-x-12 gap-y-2 text-[12px] font-bold uppercase tracking-wider opacity-90">
                                <span>No : DOK/012/11/2026</span>
                                <span>Kategori : Proposal</span>
                                <span>Divisi : Sekeretariayt</span>
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

                <div class="flex justify-center mt-20 gap-2">
                    <button v-for="p in ['<<', '<', '1', '2', '...', '4', '>', '>>']" :key="p"
                            class="w-10 h-10 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-[10px] font-bold text-slate-500 hover:bg-[#2c52a7] hover:text-white transition shadow-sm">
                        {{ p }}
                    </button>
                </div>
            </div>
        </main>

        <FooterBaru />
    </div>
</template>