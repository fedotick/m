<?php
// File: medic.php
include 'includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nume = $_POST['nume'];
        $prenume = $_POST['prenume'];
        $specializare = $_POST['specializare'];
        $tarif = $_POST['tarif'];
        if (!empty($nume) && !empty($prenume) && !empty($specializare) && !empty($tarif)) {
            $stmt = $pdo->prepare("INSERT INTO Medic (Nume, Prenume, Specializare, TarifConsultatie) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nume, $prenume, $specializare, $tarif]);
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Medic adăugat cu succes!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Toate câmpurile sunt obligatorii!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $nume = $_POST['nume'];
        $prenume = $_POST['prenume'];
        $specializare = $_POST['specializare'];
        $tarif = $_POST['tarif'];
        if (!empty($nume) && !empty($prenume) && !empty($specializare) && !empty($tarif)) {
            $stmt = $pdo->prepare("UPDATE Medic SET Nume = ?, Prenume = ?, Specializare = ?, TarifConsultatie = ? WHERE IdMedic = ?");
            $stmt->execute([$nume, $prenume, $specializare, $tarif, $id]);
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Medic actualizat cu succes!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            echo '<div class="alert alert-danger alert-dismiss fade show" role="alert">Toate câmpurile sunt obligatorii!</button>';
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Medic WHERE IdMedic = ?");
    $stmt->execute([$id]);
    echo '<div class="alert alert-success fade show">Medic șters!</div>';
}

$doctors = $pdo->query("SELECT * FROM Medic")->fetchAll(PDO::FETCH_ASSOC);
$specializations = $pdo->query("SELECT DISTINCT Specializare FROM Medic")->fetchAll(PDO::FETCH_COLUMN);
?>

<h2>Gestionează Medicii</h2>
<div class="mb-3">
    <select id="filterMedic" class="form-select">
        <option value="">Toate specializările</option>
        <?php foreach ($specializations as $spec): ?>
            <option value="<?php echo $spec; ?>"><?php echo $spec; ?></option>
        <?php endforeach; ?>
    </select>
</div>
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Adaugă Medic</button>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nume</th>
            <th>Prenume</th>
            <th>Specializare</th>
            <th>Tarif</th>
            <th>Acțiuni</th>
        </tr>
    </thead>
    <tbody id="medicTable">
        <?php foreach ($doctors as $doctor): ?>
        <tr>
            <td><?php echo $doctor['IdMedic']; ?></td>
            <td><?php echo $doctor['Nume']; ?></td>
            <td><?php echo $doctor['Prenume']; ?></td>
            <td><?php echo $doctor['Specializare']; ?></td>
            <td><?php echo $doctor['TarifConsultatie']; ?></td>
            <td>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $doctor['IdMedic']; ?>">Editează</button>
                <a href="?delete=<?php echo $doctor['IdMedic']; ?>" class="btn btn-sm btn-danger delete-btn">Șterge</a>
            </td>
        </tr>
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?php echo $doctor['IdMedic']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editează Medic</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $doctor['IdMedic']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Nume</label>
                                <input type="text" name="nume" class="form-control" value="<?php echo $doctor['Nume']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prenume</label>
                                <input type="text" name="prenume" class="form-control" value="<?php echo $doctor['Prenume']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Specializare</label>
                                <input type="text" name="specializare" class="form-control" value="<?php echo $doctor['Specializare']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tarif Consultație</label>
                                <input type="number" name="tarif" class="form-control" value="<?php echo $doctor['TarifConsultatie']; ?>" required>
                            </div>
                            <button type="submit" name="edit" class="btn btn-primary">Salvează</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adaugă Medic</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Nume</label>
                        <input type="text" name="nume" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prenume</label>
                        <input type="text" name="prenume" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Specializare</label>
                        <input type="text" name="specializare" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarif Consultație</label>
                        <input type="number" name="tarif" class="form-control" required>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary">Adaugă</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>