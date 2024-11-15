<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("koneksi.php");
if (!isset($_SESSION['username'])) {
    header("Location: LoginUser.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap Offline -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <!-- Bootstrap Online -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <hr>
        <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <?php
            $id_pasien = '';
            $nama_dokter = '';
            $tgl_periksa = '';
            $catatan = '';
            $id_obat = '';

            if (isset($_GET['id'])) {
                $ambil = mysqli_query($mysqli, "SELECT * FROM periksa WHERE id='" . $_GET['id'] . "'");
                while ($row = mysqli_fetch_array($ambil)) {
                    $id_pasien = $row['id_pasien'];
                    $id_dokter = $row['id_dokter'];
                    $tgl_periksa = $row['tgl_periksa'];
                    $catatan = $row['catatan'];
                    $id_obat = $row['id_obat']; // Multiple IDs, stored as comma-separated values
                }
            ?>
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <?php } ?>

            <div class="form-group mx-sm-3 mb-2">
                <label for="inputPasien" class="form-label fw-bold">Pasien</label>
                <select class="form-control" name="id_pasien">
                    <?php
                    $pasien = mysqli_query($mysqli, "SELECT * FROM pasien");
                    while ($data = mysqli_fetch_array($pasien)) {
                        $selected = ($data['id'] == $id_pasien) ? 'selected="selected"' : '';
                    ?>
                        <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group mx-sm-3 mb-2">
                <label for="inputDokter" class="form-label fw-bold">Dokter</label>
                <select class="form-control" name="id_dokter">
                    <?php
                    $dokter = mysqli_query($mysqli, "SELECT * FROM dokter");
                    while ($data = mysqli_fetch_array($dokter)) {
                        $selected = ($data['id'] == $id_dokter) ? 'selected="selected"' : '';
                    ?>
                        <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group mx-sm-3 mb-2">
                <label for="inputTgl_periksa" class="form-label fw-bold">Tanggal Periksa</label>
                <input type="datetime-local" class="form-control" name="tgl_periksa" id="inputTgl_periksa"
                    value="<?php echo date('Y-m-d\TH:i', strtotime($tgl_periksa)); ?>">
            </div>

            <div class="form-group mx-sm-3 mb-2">
                <label for="inputCatatan" class="form-label fw-bold">Catatan</label>
                <textarea class="form-control" name="catatan" id="inputCatatan" placeholder="Catatan"><?php echo $catatan; ?></textarea>
            </div>

            <div class="form-group mx-sm-3 mb-2">
                <label for="inputObat" class="form-label fw-bold">Obat</label>
                <textarea class="form-control" name="obat" id="inputObat" placeholder="Masukkan Obat"><?php echo $id_obat; ?></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Pasien</th>
                    <th scope="col">Dokter</th>
                    <th scope="col">Tanggal Periksa</th>
                    <th scope="col">Catatan</th>
                    <th scope="col">Obat</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query(
                    $mysqli,
                    "SELECT pr.*, d.nama AS 'nama_dokter', p.nama AS 'nama_pasien'
                          FROM periksa pr
                          LEFT JOIN dokter d ON pr.id_dokter = d.id
                          LEFT JOIN pasien p ON pr.id_pasien = p.id
                          ORDER BY pr.tgl_periksa DESC"
                );
                $no = 1;
                while ($data = mysqli_fetch_array($result)) {
                ?>
                    <tr>
                        <th scope="row"><?php echo $no++ ?></th>
                        <td><?php echo $data['nama_pasien'] ?></td>
                        <td><?php echo $data['nama_dokter'] ?></td>
                        <td><?php echo $data['tgl_periksa'] ?></td>
                        <td><?php echo $data['catatan'] ?></td>
                        <td><?php echo $data['id_obat'] ?></td>
                        <td>
                            <a class="btn btn-success rounded-pill px-3" href="index.php?page=periksa&id=<?php echo $data['id'] ?>">Ubah</a>
                            <a class="btn btn-danger rounded-pill px-3" href="index.php?page=periksa&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php
    if (isset($_POST['simpan'])) {
        $id_pasien = $_POST['id_pasien'];
        $id_dokter = $_POST['id_dokter'];
        $tgl_periksa = $_POST['tgl_periksa'];
        $catatan = $_POST['catatan'];
        $id_obat = $_POST['obat']; // Get the value from textarea

        if (isset($_POST['id'])) {
            $ubah = mysqli_query($mysqli, "UPDATE periksa SET 
                                        id_pasien = '$id_pasien',
                                        id_dokter = '$id_dokter',
                                        tgl_periksa = '$tgl_periksa',
                                        catatan = '$catatan',
                                        id_obat = '$id_obat'
                                        WHERE id = '" . $_POST['id'] . "'");
        } else {
            $tambah = mysqli_query($mysqli, "INSERT INTO periksa (id_pasien, id_dokter, tgl_periksa, catatan, id_obat) 
                                        VALUES ('$id_pasien', '$id_dokter', '$tgl_periksa', '$catatan', '$id_obat')");
        }
        echo "<script>document.location='index.php?page=periksa';</script>";
    }

    if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM periksa WHERE id = '" . $_GET['id'] . "'");
        echo "<script>document.location='index.php?page=periksa';</script>";
    }
    ?>
</body>

</html>