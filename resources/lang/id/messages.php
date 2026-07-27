<?php

return [
    'auth' => [
        'email_not_verified' => 'Email kamu belum di verifikasi',
        'invalid_credentials' => 'Email atau password salah!',
        'register_success' => 'Register berhasil, silahkan lihat email kamu untuk verifikasi',
        'email_verified' => 'Email kamu sudah di verifikasi',
    ],
    'profile' => [
        'saved' => 'Profil berhasil disimpan.',
    ],
    'document' => [
        'upload_success' => 'Berhasil upload dokumen',
        'upload_failed' => 'Gagal upload: :error',
        'deleted' => 'Dokumen berhasil dihapus',
        'delete_success' => ':message',
        'delete_failed' => 'Gagal menghapus: :error',
        'delete_failed_generic' => 'Gagal menghapus dokumen',
    ],
    'application' => [
        'already_applied_html' => 'Kamu sudah melamar pekerjaan ini. Silakan cek halaman <a href=":url"><u>Lamaran Saya</u></a> untuk melihat status lamaran kamu.',
        'already_applied' => 'Kamu sudah melamar lowongan pekerjaan ini',
        'complete_profile_first' => 'Lengkapi profil pendidikan terlebih dahulu sebelum melamar.',
        'batch_expired' => 'Maaf, periode pendaftaran untuk lowongan ini sudah berakhir.',
        'education_not_met' => 'Pendidikan terakhir kamu (:current) belum memenuhi syarat minimum (:required) untuk lowongan ini.',
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
