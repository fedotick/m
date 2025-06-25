<?php
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
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Пациент успешно добавлен!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Все поля обязательны для заполнения!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
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
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Пациент успешно обновлен!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Все поля обязательны для заполнения!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Pacient WHERE IdPacient = ?");
    $stmt->execute([$id]);
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Пациент успешно удален!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

$patients = $pdo->query("SELECT * FROM Pacient")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Управление пациентами</h2>
<div class="mb-3">
    <input type="text" id="filterPacient" class="form-control" placeholder="Фильтровать по фамилии или телефону">
</div>
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Добавить пациента</button>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Действия</th>
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
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $patient['IdPacient']; ?>">Редактировать</button>
                <a href="?delete=<?php echo $patient['IdPacient']; ?>" class="btn btn-sm btn-danger delete-btn">Удалить</a>
            </td>
        </tr>
        <!-- Модальное окно редактирования -->
        <div class="modal fade" id="editModal<?php echo $patient['IdPacient']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать пациента</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $patient['IdPacient']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Фамилия</label>
                                <input type="text" name="nume" class="form-control" value="<?php echo $patient['Nume']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Имя</label>
                                <input type="text" name="prenume" class="form-control" value="<?php echo $patient['Prenume']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Телефон</label>
                                <input type="text" name="telefon" class="form-control" value="<?php echo $patient['Telefon']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $patient['Email']; ?>" required>
                            </div>
                            <button type="submit" name="edit" class="btn btn-primary">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Модальное окно добавления -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить пациента</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Фамилия</label>
                        <input type="text" name="nume" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input type="text" name="prenume" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Телефон</label>
                        <input type="text" name="telefon" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>