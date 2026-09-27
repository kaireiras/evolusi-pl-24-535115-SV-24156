import { describe, it, expect } from 'vitest'

function formatPengirim(nama) {
  if (!nama) return 'Anonim'
  return nama.trim().toUpperCase()
}

describe('Logika Format Pengirim', () => {
  it('mengubah nama pengirim menjadi huruf kapital', () => {
    expect(formatPengirim('kaireiras')).toBe('KAIREIRAS')
  })

  it('mengembalikan Anonim jika nama kosong', () => {
    expect(formatPengirim('')).toBe('Anonim')
  })
})