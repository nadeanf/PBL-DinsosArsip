import { test, expect } from '@playwright/test';

test('Login Debug', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/login');

  await page.locator('input[type="email"]').fill(
    'gustivirdilaarumpramesti@gmail.com'
  );

  await page.locator('input[type="password"]').fill(
    '12345678'
  );

  console.log(
    await page.locator('input[type="email"]').inputValue()
  );

  console.log(
    await page.locator('input[type="password"]').inputValue()
  );

  await page.getByRole('button', { name: 'MASUK' }).click();

  await page.waitForTimeout(10000);

  console.log('URL:', await page.url());
});