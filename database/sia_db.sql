-- =========================================================
-- Sistem Informasi Akademik (SIA) - Database Schema
-- =========================================================

CREATE DATABASE IF NOT EXISTS sia_db;
USE sia_db;

-- ---------------------------------------------------------
-- Tabel users (akun login untuk semua role)
-- ---------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'dosen', 'mahasiswa') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel mahasiswa
-- ---------------------------------------------------------
CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(100) NOT NULL,
    angkatan YEAR NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel dosen
-- ---------------------------------------------------------
CREATE TABLE dosen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nip VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel mata kuliah
-- ---------------------------------------------------------
CREATE TABLE matakuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_mk VARCHAR(20) NOT NULL UNIQUE,
    nama_mk VARCHAR(150) NOT NULL,
    sks INT NOT NULL,
    semester INT NOT NULL,
    dosen_id INT NULL,
    FOREIGN KEY (dosen_id) REFERENCES dosen(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel KRS (Kartu Rencana Studi)
-- ---------------------------------------------------------
CREATE TABLE krs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    matakuliah_id INT NOT NULL,
    tahun_ajaran VARCHAR(9) NOT NULL, -- contoh: 2025/2026
    semester_ke INT NOT NULL,          -- semester berjalan mahasiswa
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (matakuliah_id) REFERENCES matakuliah(id) ON DELETE CASCADE,
    UNIQUE KEY unique_krs (mahasiswa_id, matakuliah_id, tahun_ajaran)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel nilai
-- ---------------------------------------------------------
CREATE TABLE nilai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    krs_id INT NOT NULL UNIQUE,
    nilai_angka DECIMAL(5,2) NULL,     -- 0 - 100
    nilai_huruf CHAR(2) NULL,          -- A, AB, B, BC, C, D, E
    bobot DECIMAL(3,2) NULL,           -- 4.00, 3.50, dst
    FOREIGN KEY (krs_id) REFERENCES krs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Data awal (seed) - password default: "password" (bcrypt hash sama)
-- Hash di bawah adalah hasil password_hash('password', PASSWORD_DEFAULT)
-- ---------------------------------------------------------
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('dosen1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dosen'),
('mhs1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mahasiswa');

INSERT INTO dosen (user_id, nip, nama) VALUES
(2, '198001012010011001', 'Dr. Budi Santoso, M.Kom');

INSERT INTO mahasiswa (user_id, nim, nama, prodi, angkatan) VALUES
(3, '2023010001', 'Andi Wijaya', 'Teknik Informatika', 2023);

INSERT INTO matakuliah (kode_mk, nama_mk, sks, semester, dosen_id) VALUES
('IF101', 'Algoritma dan Pemrograman', 3, 1, 1),
('IF102', 'Basis Data', 3, 2, 1),
('IF103', 'Struktur Data', 3, 2, 1),
('IF104', 'Pemrograman Web', 3, 3, 1);
