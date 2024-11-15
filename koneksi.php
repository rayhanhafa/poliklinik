<?php
$databaseHost = 'localhost';
$databaseName = 'poliklinik';
$databaseUsername = 'root';
$databasePassword = '';

$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$mysqli) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
/// Define the function to get periksa information by ID
function getPeriksaInfo($periksaId)
{
    global $mysqli; // Assuming $mysqli is your database connection variable

    $result = mysqli_query($mysqli, "SELECT * FROM periksa WHERE id = '$periksaId'");
    $periksaInfo = mysqli_fetch_assoc($result);

    return $periksaInfo;
}

// Define the function to get information for selected obats
function getSelectedObatsInfo($selectedObats)
{
    global $mysqli; // Assuming $mysqli is your database connection variable

    $selectedObatsStr = implode(',', $selectedObats);
    $result = mysqli_query($mysqli, "SELECT * FROM obat WHERE id IN ($selectedObatsStr)");
    $selectedObatsInfo = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $selectedObatsInfo;
}
function getDoctorInfo($doctorId)
{
    global $mysqli; // Change this line to access the global variable

    $query = "SELECT * FROM dokter WHERE id = '$doctorId'";
    $result = mysqli_query($mysqli, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($mysqli));
    }

    return mysqli_fetch_assoc($result);
}

function getPatientInfo($patientId)
{
    global $mysqli; // Change this line to access the global variable

    $query = "SELECT * FROM pasien WHERE id = '$patientId'";
    $result = mysqli_query($mysqli, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($mysqli));
    }

    return mysqli_fetch_assoc($result);
}
