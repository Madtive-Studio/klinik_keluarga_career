<?php

return [
    'auth' => [
        'email_not_verified' => 'Email Anda belum diverifikasi.',
        'invalid_credentials' => 'Email atau password salah!',
        'register_success' => 'Pendaftaran berhasil, silakan periksa kotak masuk email Anda untuk melakukan verifikasi.',
        'email_verified' => 'Email Anda sudah diverifikasi.',
    ],
    'profile' => [
        'saved' => 'Profil berhasil disimpan.',
    ],
    'document' => [
        'upload_success' => 'Dokumen berhasil diunggah.',
        'upload_failed' => 'Gagal mengunggah dokumen: :error',
        'deleted' => 'Dokumen berhasil dihapus.',
        'delete_success' => ':message',
        'delete_failed' => 'Gagal menghapus: :error',
        'delete_failed_generic' => 'Gagal menghapus dokumen.',
    ],
    'application' => [
        'already_applied_html' => 'Anda sudah melamar pekerjaan ini. Silakan cek halaman <a href=":url"><u>Lamaran Saya</u></a> untuk melihat status lamaran Anda.',
        'already_applied' => 'Anda sudah melamar lowongan pekerjaan ini.',
        'complete_profile_first' => 'Lengkapi profil pendidikan terlebih dahulu sebelum melamar.',
        'batch_expired' => 'Maaf, periode pendaftaran untuk lowongan ini sudah berakhir.',
        'education_not_met' => 'Pendidikan terakhir Anda (:current) belum memenuhi syarat minimum (:required) untuk lowongan ini.',
        'not_found' => 'Data lamaran tidak ditemukan.',
    ],
    'admin' => [
        'auth' => [
            'invalid_credentials' => 'Email atau password salah!',
        ],
        'profile' => [
            'updated' => 'Profil berhasil diperbarui.',
        ],
        'batch' => [
            'created' => 'Berhasil membuat batch baru',
            'updated' => 'Berhasil mengubah data batch',
            'deleted' => 'Berhasil menghapus data batch',
            'status_updated' => 'Berhasil ubah status batch',
        ],
        'category' => [
            'created' => 'Berhasil membuat kategori baru',
            'updated' => 'Berhasil mengubah data kategori',
            'deleted' => 'Berhasil menghapus data kategori',
        ],
        'job' => [
            'created' => 'Berhasil membuat lowongan pekerjaan baru',
            'updated' => 'Berhasil mengubah data lowongan pekerjaan',
            'deleted' => 'Berhasil menghapus data lowongan pekerjaan',
        ],
        'apply' => [
            'status_updated' => 'Berhasil mengubah status lamaran kandidat',
        ],
        'schedule_interview' => [
            'invalid_apply' => 'Data lamaran tidak valid',
            'created' => 'Berhasil membuat jadwal wawancara baru',
            'resent' => 'Berhasil mengirim ulang undangan wawancara',
            'updated' => 'Berhasil mengubah data jadwal wawancara',
            'deleted' => 'Berhasil menghapus data jadwal wawancara',
        ],
    ],
];
