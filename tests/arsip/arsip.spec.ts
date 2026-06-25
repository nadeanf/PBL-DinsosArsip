import { test, expect } from '@playwright/test'

test.describe('Fitur Arsip', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/login')

    await page.locator('input[type="email"]')
      .fill('gustivirdilaarumpramesti@gmail.com')

    await page.locator('input[type="password"]')
      .fill('12345678')

    await page.getByRole('button', {
      name: /masuk/i
    }).click()

    await page.waitForURL(/dashboard/)
  })
test('TC0044 - Upload tanpa file', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/unggah/aktif-inaktif')

  const textboxes = page.getByRole('textbox')

  await textboxes.nth(0).fill('Dokumen Testing') // Judul
  await textboxes.nth(1).fill('123456')          // Nomor

  await page.locator('select')
    .selectOption({ index: 1 })

  await page.getByRole('radio', {
    name: /publik/i
  }).check()

  await textboxes.nth(2).fill('Lemari Arsip')     // Lokasi
  await textboxes.nth(3).fill('Deskripsi Testing') // Deskripsi

  await page.getByRole('button', {
    name: /simpan/i
  }).click()

  await expect(page.locator('body'))
    .toContainText(/file|wajib|required/i)
})
test('TC0046 - Upload arsip valid', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/unggah/aktif-inaktif')

  const judul = `Dokumen Test ${Date.now()}`
  const nomor = `DOC-${Date.now()}`

  await page.locator('input[type="file"]')
  .setInputFiles('tests/fixtures/wpp.jpg')

  const textboxes = page.getByRole('textbox')

  await textboxes.nth(0).fill(judul)
  await textboxes.nth(1).fill(nomor)

  await page.locator('select')
    .selectOption('2026')

  await page.getByText('Pilih Kategori').click()

  await page.getByText('Bantuan Sosial').click()

  await page.getByRole('radio', {
    name: /publik/i
  }).check()

  await textboxes.nth(2).fill('Lemari Arsip')
  await textboxes.nth(3).fill('Dokumen testing')

  await page.getByRole('button', {
    name: /simpan/i
  }).click()

  await page.waitForURL(/kelola-arsip/)

  await expect(page).toHaveURL(/kelola-arsip/)
})
test('TC0047 - Metadata valid', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/unggah/aktif-inaktif')

  await page.locator('input[type="file"]')
    .setInputFiles('tests/fixtures/wpp.jpg')

  const textboxes = page.getByRole('textbox')

  await textboxes.nth(0).fill(`Metadata Valid ${Date.now()}`)
  await textboxes.nth(1).fill(`META-${Date.now()}`)

  await page.locator('select')
    .selectOption('2026')

  // kategori
  await page.getByText('Pilih Kategori').click()
  await page.getByText('Bantuan Sosial').click()

  // status akses
  await page.getByRole('radio', {
    name: /publik/i
  }).check()

  // lokasi
  await textboxes.nth(2).fill('Rak A-01')

  // deskripsi
  await textboxes.nth(3).fill('Testing metadata valid')

  await page.getByRole('button', {
    name: /simpan/i
  }).click()

  await page.waitForURL(/kelola-arsip/)

  await expect(page).toHaveURL(/kelola-arsip/)
})
test('TC0048 - Metadata kosong', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/unggah/aktif-inaktif')

  await page.getByRole('button', {
    name: /simpan/i
  }).click()

  await expect(page.locator('body'))
    .toContainText(/judul|nomor|kategori|lokasi|deskripsi|file/i)
})
test('TC0049 - Nomor surat duplikat', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/unggah/aktif-inaktif')

  await page.locator('input[type="file"]')
    .setInputFiles('tests/fixtures/wpp.jpg')

  const textboxes = page.getByRole('textbox')

  await textboxes.nth(0).fill('Dokumen Duplikat')
  await textboxes.nth(1).fill('470/099/dinsos/2024')

  await page.locator('select')
    .selectOption('2026')

  await page.getByText('Pilih Kategori').click()
  await page.getByText('Bantuan Sosial').click()

  await page.getByRole('radio', {
    name: /publik/i
  }).check()

  await textboxes.nth(2).fill('Rak Arsip')
  await textboxes.nth(3).fill('Testing duplikat')

  await page.getByRole('button', {
    name: /simpan/i
  }).click()

  await expect(page.locator('body'))
    .toContainText(/nomor|unique|sudah/i)
})
test('TC0050 - Hapus Arsip', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/kelola-arsip')

  // 🔥 pakai card arsip, bukan button
  const arsipCards = page.locator('.arsip-item')

  const beforeCount = await arsipCards.count()

  // klik tombol hapus di item pertama
  await page.getByRole('button', {
    name: /hapus/i
  }).first().click()

  // klik konfirmasi hapus di modal
  await page.getByRole('button', {
    name: /^hapus$/i
  }).last().click()

  // tunggu UI update
  await page.waitForLoadState('networkidle')

  const afterCount = await arsipCards.count()

  expect(afterCount).toBeLessThan(beforeCount)
})
test('TC0052 - Edit Metadata Arsip', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/kelola-arsip')

  // klik edit arsip pertama
  await page.getByRole('button', {
    name: /edit/i
  }).first().click()

  // 🔥 pastikan tombol simpan sudah muncul
  const saveBtn = page.getByRole('button', {
    name: /simpan/i
  })

  await expect(saveBtn).toBeVisible()

  const judulBaru = `Metadata Edit ${Date.now()}`

  // ubah judul (textbox pertama)
  const textboxes = page.getByRole('textbox')
  await textboxes.first().clear()
  await textboxes.first().fill(judulBaru)

  // simpan + tunggu redirect bareng
  await Promise.all([
    page.waitForURL(/kelola-arsip/),
    saveBtn.click()
  ])

  // pastikan kembali ke halaman list
  await expect(page).toHaveURL(/kelola-arsip/)

  // pastikan data baru muncul
  await expect(page.locator('body')).toContainText(judulBaru)
})
test('TC0053 - Edit File Arsip', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/kelola-arsip')

  await page.getByRole('button', { name: /edit/i }).first().click()

  await page.waitForLoadState('networkidle')

  await page.locator('input[type="file"]')
    .setInputFiles('tests/fixtures/wpp.jpg')

  await Promise.all([
    page.waitForURL(/kelola-arsip/),
    page.getByRole('button', { name: /simpan/i }).click()
  ])

  await expect(page).toHaveURL(/kelola-arsip/)
})
test('TC0053 - Hapus Arsip Permanen', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/sampah')

  const beforeCount = await page.getByRole('button', {
    name: /hapus permanen|delete permanent/i
  }).count()

  await page.getByRole('button', {
    name: /hapus permanen|delete permanent/i
  }).first().click()

  await expect(
    page.getByText(/yakin.*hapus permanen|tidak bisa dikembalikan/i)
  ).toBeVisible()

  await page.getByRole('button', {
    name: /hapus|ya|confirm/i
  }).last().click()

  await page.waitForLoadState('networkidle')

  const afterCount = await page.getByRole('button', {
    name: /hapus permanen|delete permanent/i
  }).count()

  expect(afterCount).toBeLessThan(beforeCount)
})
})