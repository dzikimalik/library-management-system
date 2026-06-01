-- ============================================================
-- Database: perpusatadei
-- Library Management System - SMP Negeri 3 Atadei
-- ============================================================

CREATE DATABASE IF NOT EXISTS perpusatadei
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE perpusatadei;

-- ------------------------------------------------------------
-- 1. roles
-- ------------------------------------------------------------
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_role VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. users
-- ------------------------------------------------------------
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_users_role_id (role_id),
  INDEX idx_users_status (status),
  CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. admin
-- ------------------------------------------------------------
CREATE TABLE admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  nama VARCHAR(100) NOT NULL,
  nip VARCHAR(50) NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_admin_user_id (user_id),
  CONSTRAINT fk_admin_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. kepala
-- ------------------------------------------------------------
CREATE TABLE kepala (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  nama VARCHAR(100) NOT NULL,
  nip VARCHAR(50) NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_kepala_user_id (user_id),
  CONSTRAINT fk_kepala_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. pegawai
-- ------------------------------------------------------------
CREATE TABLE pegawai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  nama VARCHAR(100) NOT NULL,
  nip VARCHAR(50) NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_pegawai_user_id (user_id),
  CONSTRAINT fk_pegawai_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. kelas
-- ------------------------------------------------------------
CREATE TABLE kelas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kelas VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. anggota
-- ------------------------------------------------------------
CREATE TABLE anggota (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  nama VARCHAR(100) NOT NULL,
  nis VARCHAR(50) NOT NULL,
  kelas_id INT NOT NULL,
  no_telp VARCHAR(20) NOT NULL,
  alamat TEXT NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_anggota_user_id (user_id),
  INDEX idx_anggota_kelas_id (kelas_id),
  CONSTRAINT fk_anggota_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_anggota_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 8. buku
-- ------------------------------------------------------------
CREATE TABLE buku (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  pengarang VARCHAR(100) NOT NULL,
  penerbit VARCHAR(100) NOT NULL,
  isbn VARCHAR(50) NOT NULL,
  tahun YEAR NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  stok INT DEFAULT 0,
  rak VARCHAR(20) NOT NULL,
  cover VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_buku_kategori (kategori),
  INDEX idx_buku_judul (judul)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 9. peminjaman
-- ------------------------------------------------------------
CREATE TABLE peminjaman (
  id INT AUTO_INCREMENT PRIMARY KEY,
  anggota_id INT NOT NULL,
  buku_id INT NOT NULL,
  user_id INT NOT NULL,
  tgl_pinjam DATE NOT NULL,
  tgl_jatuh_tempo DATE NOT NULL,
  tgl_kembali DATE DEFAULT NULL,
  status ENUM('dipinjam','dikembalikan','terlambat') DEFAULT 'dipinjam',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_peminjaman_anggota_id (anggota_id),
  INDEX idx_peminjaman_buku_id (buku_id),
  INDEX idx_peminjaman_user_id (user_id),
  INDEX idx_peminjaman_status (status),
  CONSTRAINT fk_peminjaman_anggota FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE,
  CONSTRAINT fk_peminjaman_buku FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE,
  CONSTRAINT fk_peminjaman_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 10. pengembalian
-- ------------------------------------------------------------
CREATE TABLE pengembalian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  peminjaman_id INT NOT NULL,
  user_id INT NOT NULL,
  tgl_kembali DATE NOT NULL,
  denda INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_pengembalian_peminjaman_id (peminjaman_id),
  INDEX idx_pengembalian_user_id (user_id),
  CONSTRAINT fk_pengembalian_peminjaman FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE,
  CONSTRAINT fk_pengembalian_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 11. denda
-- ------------------------------------------------------------
CREATE TABLE denda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pengembalian_id INT NOT NULL,
  jumlah_denda INT DEFAULT 0,
  status_bayar ENUM('lunas','belum') DEFAULT 'belum',
  tgl_bayar DATE DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_denda_pengembalian_id (pengembalian_id),
  INDEX idx_denda_status_bayar (status_bayar),
  CONSTRAINT fk_denda_pengembalian FOREIGN KEY (pengembalian_id) REFERENCES pengembalian(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 12. kunjungan
-- ------------------------------------------------------------
CREATE TABLE kunjungan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  anggota_id INT DEFAULT NULL,
  nama_pengunjung VARCHAR(100) NOT NULL,
  tgl_kunjungan DATE NOT NULL,
  keperluan TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_kunjungan_anggota_id (anggota_id),
  INDEX idx_kunjungan_tgl (tgl_kunjungan),
  CONSTRAINT fk_kunjungan_anggota FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA
-- ============================================================

-- ------------------------------------------------------------
-- roles (4 records)
-- ------------------------------------------------------------
INSERT INTO roles (id, nama_role) VALUES
(1, 'Admin'),
(2, 'Kepala'),
(3, 'Pegawai'),
(4, 'Anggota');

-- ------------------------------------------------------------
-- users (4 records)
-- bcrypt hash for 'admin123', 'kepala123', 'pegawai123', 'anggota123'
-- ------------------------------------------------------------
INSERT INTO users (id, username, password, role_id, status) VALUES
(1, 'admin',   '$2y$10$m7zAgr2HJLQDBS98a74tWOfJ9z5Kdl1rzYdtI0KOPddMKSC5AYo0a', 1, 'aktif'),
(2, 'kepala',  '$2y$10$c2xktf2LiSTdURnNkPZz2Ost86z4B7p3lNmdMj9KRrMbkMpm0agHC', 2, 'aktif'),
(3, 'pegawai', '$2y$10$mNlIbhwRCgY1XMvw6mOJj.2Wkp84HOVyCdXXILq2kH2gF7fnUfeiu', 3, 'aktif'),
(4, 'anggota', '$2y$10$fEYi5d9fKMPB3Wlvdmy0M.I8X1X5HClWJfE3RveMTbBPurm3AITHy', 4, 'aktif');

-- ------------------------------------------------------------
-- admin (1 record)
-- ------------------------------------------------------------
INSERT INTO admin (user_id, nama, nip) VALUES
(1, 'Administrator', '199001012022011001');

-- ------------------------------------------------------------
-- kepala (1 record)
-- ------------------------------------------------------------
INSERT INTO kepala (user_id, nama, nip) VALUES
(2, 'Kepala Perpustakaan', '198505152022021002');

-- ------------------------------------------------------------
-- pegawai (1 record)
-- ------------------------------------------------------------
INSERT INTO pegawai (user_id, nama, nip) VALUES
(3, 'Pegawai Perpustakaan', '199203202022031003');

-- ------------------------------------------------------------
-- kelas (3 records) - SMP
-- ------------------------------------------------------------
INSERT INTO kelas (id, nama_kelas) VALUES
(1, 'VII'),
(2, 'VIII'),
(3, 'IX');

-- ------------------------------------------------------------
-- anggota (1 record)
-- ------------------------------------------------------------
INSERT INTO anggota (user_id, nama, nis, kelas_id, no_telp, alamat) VALUES
(4, 'Siswa Perpustakaan', '2024001', 1, '081234567890', 'Jl. Merdeka No. 123, Kota');

-- ------------------------------------------------------------
-- buku (5 records)
-- ------------------------------------------------------------
INSERT INTO buku (judul, pengarang, penerbit, isbn, tahun, kategori, stok, rak) VALUES
('Pemrograman Web dengan PHP & MySQL', 'Budi Raharjo', 'Informatika', '978-602-151-123-4', 2023, 'Teknologi', 10, 'RAK-A1'),
('Basis Data Lanjutan', 'Fathansyah', 'Andi Offset',   '978-979-29-123-5', 2022, 'Teknologi',  8, 'RAK-A2'),
('Algoritma dan Struktur Data', 'Mohamad Irfan', 'Elex Media', '978-602-04-567-8', 2021, 'Pemrograman', 5, 'RAK-B1'),
('Jaringan Komputer', 'Iwan Sofana', 'Informatika',  '978-623-713-345-6', 2023, 'Jaringan',   7, 'RAK-C1'),
('Pemrograman Berorientasi Objek dengan Java', 'Rosa A.S.', 'Modula', '978-602-876-456-7', 2022, 'Pemrograman', 6, 'RAK-B2');

-- ------------------------------------------------------------
-- peminjaman (1 record)
-- ------------------------------------------------------------
INSERT INTO peminjaman (anggota_id, buku_id, user_id, tgl_pinjam, tgl_jatuh_tempo, tgl_kembali, status) VALUES
(1, 1, 3, '2026-05-10', '2026-05-24', '2026-05-17', 'dikembalikan');

-- ------------------------------------------------------------
-- pengembalian (1 record)
-- ------------------------------------------------------------
INSERT INTO pengembalian (peminjaman_id, user_id, tgl_kembali, denda) VALUES
(1, 3, '2026-05-17', 0);

-- ------------------------------------------------------------
-- denda (1 record)
-- ------------------------------------------------------------
INSERT INTO denda (pengembalian_id, jumlah_denda, status_bayar, tgl_bayar) VALUES
(1, 0, 'lunas', '2026-05-17');

-- ------------------------------------------------------------
-- kunjungan (1 record)
-- ------------------------------------------------------------
INSERT INTO kunjungan (anggota_id, nama_pengunjung, tgl_kunjungan, keperluan) VALUES
(1, 'Siswa Perpustakaan', '2026-05-17', 'Membaca buku dan meminjam buku pemrograman web');
