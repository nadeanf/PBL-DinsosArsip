<template>
  <footer class="footer">
    <div class="container">

      <!-- Kiri -->
      <div class="section">
        <h3>Sistem Arsip Digital</h3>
        <p>
          Platform digital untuk pengelolaan arsip dokumen pemerintahan
          yang aman, efisien, dan terintegrasi.
        </p>
      </div>

      <!-- Tengah -->
      <div class="section">
        <h3>Sistem Arsip Digital</h3>

        <div class="item">
          <span>📍</span>
          <p>Jalan Kebo Kenongo Tegalarum, Kemiri, Kecamatan Mojosongo, Kabupaten Boyolali, Jawa Tengah 57311</p>
        </div>

        <div class="item">
          <span>📞</span>
          <p>(0276) 321047</p>
        </div>

        <div class="item">
          <span>✉️</span>
          <p>dinsos@boyolali.go.id</p>
        </div>

        <div class="item">
          <span>🕒</span>
          <p>
            Senin - Jum'at: 07.00 - 17.00 <br>
            Sabtu - Minggu: Tutup
          </p>
        </div>
      </div>

      <!-- Kanan -->
     <div class="section">
  <h3>Lokasi Kami</h3>

  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.2409728676066!2d110.60317497357615!3d-7.548680674525139!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a695aa55a4367%3A0x444b7fb94b08e7c2!2sDinas%20Sosial%20Kabupaten%20Boyolali!5e0!3m2!1sen!2sid!4v1776332122826!5m2!1sen!2sid" width="400" height="220" style="border:0;" allowfullscreen="true" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

</div>

    </div>
  </footer>
</template>

<style scoped>
.footer {
  background-color: #3f5ba9;
  color: white;
  padding: 40px 60px;
}

.container {
  display: flex;
  justify-content: space-between;
  gap: 40px;
}

.section {
  flex: 1;
}

.section h3 {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 15px;
}

.section p {
  font-size: 14px;
  line-height: 1.6;
}

.item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 10px;
}

.item span {
  font-size: 16px;
  margin-top: 3px;
}

.section img {
  width: 100%;
  max-width: 250px;
  border-radius: 4px;
}
</style>