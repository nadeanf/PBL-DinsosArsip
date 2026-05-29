<template>
  <footer class="bg-blue-700 dark:bg-blue-900 text-white py-8 md:py-12 px-4 md:px-6">
    <!-- Main Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 max-w-6xl mx-auto mb-8 md:mb-12">
      <!-- Section 1: About -->
      <div class="space-y-3">
        <h3 class="text-lg md:text-xl font-bold">Sistem Arsip Digital</h3>
        <p class="text-sm md:text-base text-blue-100 leading-relaxed">
          Platform digital untuk pengelolaan arsip dokumen pemerintahan
          yang aman, efisien, dan terintegrasi.
        </p>
      </div>

      <!-- Section 2: Contact Info -->
      <div class="space-y-3">
        <h3 class="text-lg md:text-xl font-bold">Kontak Kami</h3>
        <div class="space-y-2 text-sm md:text-base">
          <div class="flex items-start gap-3">
            <span class="text-lg flex-shrink-0">📍</span>
            <p class="text-blue-100">Jalan Kebo Kenongo Tegalarum, Kemiri, Kecamatan Mojosongo, Kabupaten Boyolali, Jawa Tengah 57311</p>
          </div>

          <div class="flex items-center gap-3">
            <span class="text-lg">📞</span>
            <p class="text-blue-100">(0276) 321047</p>
          </div>

          <div class="flex items-center gap-3">
            <span class="text-lg">✉️</span>
            <p class="text-blue-100">dinsos@boyolali.go.id</p>
          </div>

          <div class="flex items-start gap-3">
            <span class="text-lg flex-shrink-0">🕒</span>
            <p class="text-blue-100">
              Senin - Jum'at: 07.00 - 17.00 <br>
              Sabtu - Minggu: Tutup
            </p>
          </div>
        </div>
      </div>

      <!-- Section 3: Location Map -->
      <div class="space-y-3">
        <h3 class="text-lg md:text-xl font-bold">Lokasi Kami</h3>
        <div class="w-full h-48 md:h-56 rounded-lg overflow-hidden border-2 border-blue-500/50">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.2409728676066!2d110.60317497357615!3d-7.548680674525139!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a695aa55a4367%3A0x444b7fb94b08e7c2!2sDinas%20Sosial%20Kabupaten%20Boyolali!5e0!3m2!1sen!2sid!4v1776332122826!5m2!1sen!2sid" 
            class="w-full h-full" 
            style="border:0;" 
            allowfullscreen
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade"
          />
        </div>
      </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-blue-500/30 my-6 md:my-8"></div>

    <!-- Bottom Section -->
    <div class="max-w-6xl mx-auto text-center text-xs md:text-sm text-blue-100">
      <p>© 2025 Dinas Sosial Boyolali. All rights reserved.</p>
    </div>
  </footer>
</template>