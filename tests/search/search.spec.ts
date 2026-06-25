import { test, expect } from '@playwright/test'

test.describe('Fitur Search Arsip', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/login')

    await page.locator('input[type="email"]').fill('gustivirdilaarumpramesti@gmail.com')
    await page.locator('input[type="password"]').fill('12345678')

    await page.getByRole('button', { name: /masuk/i }).click()
    await page.waitForURL(/dashboard/)
  })

  test('TC0032 - Cari berdasarkan keyword', async ({ page }) => {
  const searchInput = page.getByPlaceholder('Cari dokumen...')

  await expect(searchInput).toBeVisible()
  await expect(searchInput).toBeEditable()

  await searchInput.fill('laporan')

  await Promise.all([
    page.waitForURL(/daftar-arsip/),
    page.getByRole('button', { name: /^Cari$/i }).click()
  ])

  await expect(page).toHaveURL(/search=laporan/)
})

  test('TC0033 - Filter kategori', async ({ page }) => {
  const dropdownTrigger = page.getByText('Pilih Kategori', { exact: true })
  await dropdownTrigger.click()

  const option = page.locator('text=Keuangan').last()
  await option.waitFor({ state: 'visible' })
  await option.click()

  await page.getByRole('button', { name: /cari/i }).click()

  await expect(page).toHaveURL(/kategori=/)
})

  test('TC0034 - Filter tanggal', async ({ page }) => {
    const dates = page.locator('input[type="date"]')

    await dates.nth(0).fill('2024-01-01')
    await dates.nth(1).fill('2024-12-31')

    await page.getByRole('button', { name: /cari/i }).click()
    await page.waitForLoadState('networkidle')

    await expect(page).toHaveURL(/tanggal_awal=2024-01-01/)
    await expect(page).toHaveURL(/tanggal_akhir=2024-12-31/)
  })

  test('TC0035 - Kombinasi filter', async ({ page }) => {
  await page.fill('input[placeholder="Cari dokumen..."]', 'laporan')

  const dropdownTrigger = page.getByText('Pilih Kategori', { exact: true })
  await dropdownTrigger.click()

  const option = page.locator('text=Keuangan').last()
  await option.waitFor({ state: 'visible' })
  await option.click()

  const dates = page.locator('input[type="date"]')
  await dates.nth(0).fill('2024-01-01')
  await dates.nth(1).fill('2024-12-31')

  await page.getByRole('button', { name: /cari/i }).click()

  await expect(page).toHaveURL(/search=laporan/)
  await expect(page).toHaveURL(/kategori=/)
})

})