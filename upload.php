<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Pastikan ada file yang diunggah
if (isset($_FILES['newImage'])) {
    $file = $_FILES['newImage'];

    // Direktori tempat file akan disimpan
    $uploadDirectory = 'uploads/';

    // Buat direktori jika belum ada
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    // Validasi file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = $file['type'];

    if (!in_array($fileType, $allowedTypes)) {
        $response['message'] = 'Tipe file tidak diizinkan. Hanya JPG, PNG, dan GIF.';
    } elseif ($file['size'] > 5000000) { // Maksimum 5MB
        $response['message'] = 'Ukuran file terlalu besar. Maksimum 5MB.';
    } else {
        // Ambil ekstensi file
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        // Buat nama unik untuk file
        $newFileName = uniqid() . '.' . $fileExt;
        $destination = $uploadDirectory . $newFileName;

        // Pindahkan file ke direktori tujuan
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $response['success'] = true;
            $response['message'] = 'Foto berhasil diunggah!';
            $response['filePath'] = $destination;
        } else {
            $response['message'] = 'Gagal memindahkan file.';
        }
    }
} else {
    $response['message'] = 'Tidak ada file yang diunggah.';
}

echo json_encode($response);
?>
