<script setup>
import { FileText, Eye, Download } from 'lucide-vue-next'

// TERIMA DATA DARI PARENT
defineProps({
  doc: {
    type: Object,
    default: () => ({
      title: 'Nama File',
      jenis: 'PDF',
      tanggal: '01 Januari 2026',
      status: 'Public'
    })
  }
})
</script>

<template>
<div style="padding: 24px; background: #f3f4f6; min-height: 100vh;">

  <!-- TITLE -->
  <h1 style="font-size: 22px; font-weight: 600; margin-bottom: 20px;">
    {{ doc?.title ?? 'Tidak ada data' }}
  </h1>

  <!-- CARD BIRU -->
  <div style="background:#6b95a6; padding:16px; border-radius:10px; margin-bottom:20px;">

    <!-- INNER -->
    <div style="background:#f1f1f1; height:260px; border-radius:8px; display:flex; align-items:center; justify-content:center;">

      <div style="text-align:center; color:#555;">
        <FileText style="width:40px; height:40px; margin:auto;" />
        <p style="font-size:12px;">(Pratinjau)</p>
      </div>

    </div>

  </div>

  <!-- FORM -->
  <div style="display:grid; grid-template-columns:3fr 1fr; gap:16px;">

    <!-- LEFT -->
    <div style="display:flex; flex-direction:column; gap:10px;">

      <div style="background:#6b95a6; padding:8px 16px; border-radius:999px; color:white; font-size:12px;">
        Jenis File: <b>{{ doc?.jenis ?? '-' }}</b>
      </div>

      <div style="background:#6b95a6; padding:8px 16px; border-radius:999px; color:white; font-size:12px;">
        Tanggal: <b>{{ doc?.tanggal ?? '-' }}</b>
      </div>

      <div style="background:#6b95a6; padding:8px 16px; border-radius:999px; color:white; font-size:12px;">
        Akses: <b>{{ doc?.status ?? '-' }}</b>
      </div>

    </div>

    <!-- RIGHT -->
    <div style="display:flex; flex-direction:column; gap:10px;">

      <button
        style="background:#86a7b4; height:60px; border-radius:8px; display:flex; flex-direction:column; align-items:center; justify-content:center; font-size:12px;"
      >
        <Eye style="width:16px; height:16px; margin-bottom:4px;" />
        Lihat
      </button>

      <button
        style="background:#86a7b4; height:60px; border-radius:8px; display:flex; flex-direction:column; align-items:center; justify-content:center; font-size:12px;"
      >
        <Download style="width:16px; height:16px; margin-bottom:4px;" />
        Unduh
      </button>

    </div>

  </div>

</div>
</template>