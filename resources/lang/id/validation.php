<?php

return [
    'required' => ':attribute wajib diisi.',
    'exists' => ':attribute tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'min' => [
        'numeric' => ':attribute minimal :min.',
        'string' => ':attribute minimal :min karakter.',
    ],
    'max' => [
        'numeric' => ':attribute maksimal :max.',
        'string' => ':attribute maksimal :max karakter.',
        'file' => ':attribute maksimal :max kilobyte.',
    ],
    'email' => ':attribute harus berupa email yang valid.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'date' => ':attribute bukan tanggal yang valid.',
    'file' => ':attribute harus berupa file.',
    'mimes' => 'Format :attribute harus :values.',
    'uploaded' => ':attribute gagal diunggah. Pastikan ukuran file sesuai dan coba lagi.',
    'string' => ':attribute harus berupa teks.',
    'current_password' => 'Password saat ini tidak sesuai.',

    'attributes' => [
        'batch_id' => 'batch',
        'category_id' => 'kategori',
        'title' => 'judul lowongan',
        'type' => 'tipe pekerjaan',
        'quota' => 'kuota',
        'salary' => 'gaji',
        'experience' => 'pengalaman',
        'qualification' => 'kualifikasi',
        'description' => 'deskripsi',
        'min_education' => 'minimum pendidikan',
        'required_skills' => 'skill wajib',
        'weight_education' => 'bobot pendidikan',
        'weight_experience' => 'bobot pengalaman',
        'weight_skills' => 'bobot skill',
        'weight_profile' => 'bobot kelengkapan profil',
        'weight_cover_letter' => 'bobot cover letter',
        'threshold_shortlist' => 'batas skor direkomendasi',
        'threshold_reject' => 'batas skor review',
        'job_uuid' => 'lowongan',
        'type_of_document' => 'jenis dokumen',
        'cover_letter' => 'surat lamaran',
        'new_document' => 'berkas CV',
        'document_id' => 'dokumen CV',
        'file' => 'file',
        'education_level' => 'tingkat pendidikan',
        'years_of_experience' => 'pengalaman kerja',
        'skills.*.name' => 'nama skill',
        'name' => 'nama',
        'email' => 'email',
        'current_password' => 'password saat ini',
        'password' => 'password baru',
    ],

    'custom' => [
        'job_uuid' => [
            'required' => 'Lowongan tidak valid.',
            'exists' => 'Lowongan pekerjaan tidak ditemukan.',
        ],
        'type_of_document' => [
            'required' => 'Pilih jenis dokumen terlebih dahulu.',
        ],
        'cover_letter' => [
            'required' => 'Surat lamaran wajib diisi.',
        ],
        'description' => [
            'required' => 'Deskripsi wajib diisi.',
        ],
        'new_document' => [
            'required' => 'File dokumen wajib diupload.',
            'file' => 'Berkas CV harus berupa file yang valid.',
            'uploaded' => 'File tidak berhasil diunggah ke server. Pastikan ukuran maksimal 20MB dan coba lagi.',
            'mimes' => 'Format file harus PDF, DOC, atau DOCX.',
            'max' => 'Ukuran file maksimal 20MB.',
        ],
        'document_id' => [
            'required' => 'Pilih dokumen CV/Resume terlebih dahulu.',
        ],
        'file' => [
            'required' => 'File wajib diisi.',
            'file' => 'File harus berupa file.',
            'mimes' => 'File harus berupa Gambar, PDF, Word, atau Excel.',
            'max' => 'Ukuran file harus kurang dari 20MB.',
        ],
        'type' => [
            'required' => 'Tipe wajib diisi.',
            'string' => 'Tipe harus berupa teks.',
        ],
        'weight_education' => [
            'weight_total' => 'Total bobot penilaian harus 100. Saat ini totalnya :total.',
            'threshold_order' => 'Batas skor direkomendasi harus lebih besar dari batas skor review.',
        ],
    ],
];
