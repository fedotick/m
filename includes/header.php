<?php
// File: includes/header.php
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Medical</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistem Medical</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="pacient.php">Pacienți</a></li>
                    <li class="nav-item"><a class="nav-link" href="medic.php">Medici</a></li>
                    <li class="nav-item"><a class="nav-link" href="consultatie.php">Consultații</a></li>
                    <li class="nav-item"><a class="nav-link" href="raport.php">Raport</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">