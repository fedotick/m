<?php
// File: pacient.php
include 'includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nume = $_POST['nume'];
        $prenume = $_POST['prenume'];
        $telefon = $_POST['telefon'];
        $email = $_POST['email'];
        if (!empty($nume) && !empty($prenume) && !empty($telefon) && !empty($email)) {
            $stmt = $pdo->prepare("INSERT INTO Pacient (Nume, Prenume, Telefon, Email) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nume, $prenume, $telefon, $email]);
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Pacient adăugat cu succes!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Toate câmpurile sunt obligatorii!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $nume = $_POST['nume'];
        $prenume = $_POST['prenume'];
        $telefon = $_POST['telefon'];
        $email = $_POST['email'];
        if (!empty($nume) && !empty($prenume) && !empty($telefon) && !empty($email)) {
            $stmt = $pdo->prepare("UPDATE Pacient SET Nume = ?, Prenume = ?, Telefon = ?, Email = ? WHERE IdPacient = ?");
            $stmt->execute([$nume, $prenume, $telefon, $email, $id]);
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Pacient actualizat cu succes!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Toate câmpurile sunt obligatorii!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Pacient WHERE IdPacient = ?");
    $stmt->execute([$id]);
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Pacient șters cu succes!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

$patients = $pdo->query("SELECT * FROM Pacient")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestioneaza Pacienți</h2>
<div class="mb-3">
    <input type="text" id="filterPacient" class="form-control" placeholder="Filtrează după nume sau telefon">
</div>
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Adaugă Pacient</button>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nume</th>
            <th>Prenume</th>
            <th>Telefon</th>
            <th>Email</th>
            <th>Acțiuni</th>
        </tr>
    </thead>
    <tbody id="pacientTable">
        <?php foreach ($patients as $patient): ?>
        <tr>
            <td><?php echo $patient['IdPacient']; ?></td>
            <td><?php echo $patient['Nume']; ?></td>
            <td><?php echo $patient['Prenume']; ?></td>
            <td><?php echo $patient['Telefon']; ?></td>
            <td><?php echo $patient['Email']; ?></td>
            <td>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $patient['IdPacient']; ?>">Editează</button>
                <a href="?delete=<?php echo $patient['IdPacient']; ?>" class="btn btn-sm btn-danger delete-btn">Șterge</a>
            </td>
        </tr>
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?php echo $patient['IdPacient']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editează Pacient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $patient['IdPacient']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Nume</label>
                                <input type="text" name="nume" class="form-control" value="<?php echo $patient['Nume']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prenume</label>
                                <input type="text" name="prenume" class="form-control" value="<?php echo $patient['Prenume']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="telefon" class="form-control" value="<?php echo $patient['Telefon']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $patient['Email']; ?>" required>
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
                <h5 class="modal-title">Adaugă Pacient</h5>
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
                        <label class="form-label">Telefon</label>
                        <input type="text" name="telefon" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary">Adaugă</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>