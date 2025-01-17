<?php
include_once "../../../config/conn.php";
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];
$id_dokter = $_SESSION['id'];

if ($akses != 'dokter') {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}

// Input data ke database
if (isset($_POST["submit"])) {
  // Cek validasi input
  if (empty($_POST["hari"]) || empty($_POST["jam_mulai"]) || empty($_POST["jam_selesai"])) {
    echo "
            <script src='../../../plugins/sweetalert2/sweetalert2.all.min.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Whoops...',
                        text: 'Data tidak boleh kosong!',
                    }).then(() => {
                        window.location.href = '../jadwal_periksa/create.php';
                    });
                });
            </script>
        ";
    die;
  } else {
    // Tambahkan data dan cek hasilnya
    $result = tambahJadwalPeriksa($_POST);
    echo "
            <script src='../../../plugins/sweetalert2/sweetalert2.all.min.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {";
    if ($result > 0) {
      echo "
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data berhasil ditambahkan!',
                    }).then(() => {
                        window.location.href = '../jadwal_periksa';
                    });
                ";
    } else if ($result == -2) {
      echo "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Dokter sudah memiliki jadwal di hari yang sama!',
                    }).then(() => {
                        window.location.href = '../jadwal_periksa/create.php';
                    });
                ";
    } else if ($result == -3) {
      echo "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Jadwal bentrok dengan dokter lain!',
                    }).then(() => {
                        window.location.href = '../jadwal_periksa/create.php';
                    });
                ";
    } else {
      echo "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Data gagal ditambahkan!',
                    }).then(() => {
                        window.location.href = '../jadwal_periksa';
                    });
                ";
    }
    echo "
                });
            </script>
        ";
  }
}
?>

<?php
$title = 'Poliklinik | Tambah Jadwal Periksa';

// Breadcrumb Section
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?= $base_dokter; ?>">Home</a></li>
  <li class="breadcrumb-item"><a href="<?= $base_dokter . '/jadwal_periksa'; ?>">Jadwal Periksa</a></li>
  <li class="breadcrumb-item active">Tambah Jadwal Periksa</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start(); ?>
Tambah Jadwal Periksa
<?php
$main_title = ob_get_clean();
ob_flush();

// Content Section
ob_start(); ?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Tambah Jadwal Periksa</h3>
  </div>
  <div class="card-body">
    <form action="" id="tambahJadwal" method="POST">
      <input type="hidden" name="id_dokter" value="<?= $id_dokter ?>">
      <div class="form-group">
        <label for="hari">Hari</label>
        <select name="hari" id="hari" class="form-control">
          <option value="">-- Pilih Hari --</option>
          <option value="Senin">Senin</option>
          <option value="Selasa">Selasa</option>
          <option value="Rabu">Rabu</option>
          <option value="Kamis">Kamis</option>
          <option value="Jumat">Jumat</option>
          <option value="Sabtu">Sabtu</option>
        </select>
      </div>
      <div class="form-group">
        <label for="jam_mulai">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control">
      </div>
      <div class="form-group">
        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control">
      </div>
      <div class="d-flex justify-content-end">
        <button type="submit" name="submit" id="submitButton" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php
$content = ob_get_clean();
ob_flush();
?>

<?php include_once "../../../layouts/index.php"; ?>