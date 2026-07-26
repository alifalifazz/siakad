<?php
// ============================================================
// Fungsi bantu umum (helper functions)
// ============================================================

/**
 * Konversi nilai angka (0-100) menjadi nilai huruf & bobot.
 * @return array [huruf, bobot]
 */
function konversi_nilai($angka) {
    if ($angka >= 85) return ['A', 4.00];
    if ($angka >= 80) return ['AB', 3.50];
    if ($angka >= 70) return ['B', 3.00];
    if ($angka >= 65) return ['BC', 2.50];
    if ($angka >= 55) return ['C', 2.00];
    if ($angka >= 40) return ['D', 1.00];
    return ['E', 0.00];
}

/**
 * Hitung IPK (Indeks Prestasi Kumulatif) mahasiswa
 * berdasarkan seluruh nilai yang sudah masuk.
 *
 * @param PDO $pdo
 * @param int $mahasiswa_id
 * @return array ['ipk' => float, 'total_sks' => int, 'detail' => array]
 */
function hitung_ipk($pdo, $mahasiswa_id) {
    $stmt = $pdo->prepare("
        SELECT mk.nama_mk, mk.sks, n.nilai_angka, n.nilai_huruf, n.bobot, krs.tahun_ajaran
        FROM krs
        JOIN matakuliah mk ON krs.matakuliah_id = mk.id
        LEFT JOIN nilai n ON n.krs_id = krs.id
        WHERE krs.mahasiswa_id = ?
        ORDER BY krs.tahun_ajaran ASC
    ");
    $stmt->execute([$mahasiswa_id]);
    $rows = $stmt->fetchAll();

    $total_sks = 0;
    $total_mutu = 0; // sks * bobot
    $detail = [];

    foreach ($rows as $row) {
        $detail[] = $row;
        if ($row['bobot'] !== null) {
            $total_sks += $row['sks'];
            $total_mutu += $row['sks'] * $row['bobot'];
        }
    }

    $ipk = $total_sks > 0 ? round($total_mutu / $total_sks, 2) : 0.00;

    return [
        'ipk' => $ipk,
        'total_sks' => $total_sks,
        'detail' => $detail,
    ];
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
