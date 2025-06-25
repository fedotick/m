<?php
include 'includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $idPacient = intval($_POST['idPacient']);
        $idMedic = intval($_POST['idMedic']);
        $data = $_POST['data'];
        $time = $_POST['ora'];
        $diagnostic = $_POST['diagnostic'];

        if (strtotime($data) < strtotime('today')) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Нельзя запланировать консультацию в прошлом!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Consultatie WHERE IdMedic = ? AND Data = ? AND Ora = ?");
            $stmt->execute([$idMedic, $data, $time]);
            if ($stmt->fetchColumn() > 0) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">У этого врача уже запланирована консультация на указанное время!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            } else {
                $stmt = $pdo->prepare("INSERT INTO Consultatie (IdPacient, IdMedic, Data, Ora, Diagnostic) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$idPacient, $idMedic, $data, $time, $diagnostic]);
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Консультация успешно запланирована!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            }
        }
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $idPacient = intval($_POST['idPacient']);
        $idMedic = intval($_POST['idMedic']);
        $data = $_POST['data'];
        $time = $_POST['time'];
        $diagnostic = $_POST['diagnostic'];

        if (strtotime($data) < strtotime('today')) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Нельзя запланировать консультацию в прошлом!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Consultatie WHERE IdMedic = ? AND Data = ? AND Ora = ? AND IdConsultatie != ?");
            $stmt->execute([$idMedic, $data, $time, $id]);
            if ($stmt->fetchColumn() > 0) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">У этого врача уже запланирована консультация на указанное время!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            } else {
                $stmt = $pdo->prepare("UPDATE Consultatie SET IdPacient = ?, IdMedic = ?, Data = ?, Ora = ?, Diagnostic = ? WHERE IdConsultatie = ?");
                $stmt->execute([$idPacient, $idMedic, $data, $time, $diagnostic, $id]);
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Консультация успешно обновлена!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Consultatie WHERE IdConsultatie = ?");
    $stmt->execute([$id]);
    echo '<div class="alert alert-success alert-dismiss fade show" role="alert">Консультация успешно отменена!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

$consultations = $pdo->query("SELECT c.*, p.Nume AS pNume, p.Prenume AS pPrenume, m.Nume AS mNume, m.Prenume AS mPrenume 
    FROM Consultatie c 
    JOIN Pacient p ON c.IdPacient = p.IdPacient 
    JOIN Medic m ON c.IdMedic = m.IdMedic")->fetchAll(PDO::FETCH_ASSOC);
$patients = $pdo->query("SELECT * FROM Pacient")->fetchAll(PDO::FETCH_ASSOC);
$doctors = $pdo->query("SELECT * FROM Medic")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Управление консультациями</h2>
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Запланировать консультацию</button>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Пациент</th>
            <th>Врач</th>
            <th>Дата</th>
            <th>Время</th>
            <th>Диагноз</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($consultations as $consultation): ?>
        <tr>
            <td><?php echo $consultation['IdConsultatie']; ?></td>
            <td><?php echo $consultation['pNume'] . ' ' . $consultation['pPrenume']; ?></td>
            <td><?php echo $consultation['mNume'] . ' ' . $consultation['mPrenume']; ?></td>
            <td><?php echo $consultation['Data']; ?></td>
            <td><?php echo $consultation['Ora']; ?></td>
            <td><?php echo $consultation['Diagnostic'] ?: '-'; ?></td>
            <td>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $consultation['IdConsultatie']; ?>">Редактировать</button>
                <a href="?delete=<?php echo $consultation['IdConsultatie']; ?>" class="btn btn-sm btn-danger delete-btn">Удалить</a>
            </td>
        </tr>
        <!-- Модальное окно редактирования -->
        <div class="modal fade" id="editModal<?php echo $consultation['IdConsultatie']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать консультацию</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $consultation['IdConsultatie']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Пациент</label>
                                <select name="idPacient" class="form-control" required>
                                    <?php foreach ($patients as $patient): ?>
                                        <option value="<?php echo $patient['IdPacient']; ?>" <?php echo ($patient['IdPacient'] == $consultation['IdPacient'])? 'selected' : ''; ?>>
                                            <?php echo $patient['Nume'] . ' ' . $patient['Prenume']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Врач</label>
                                <select name="idMedic" class="form-control" required>
                                    <?php foreach ($doctors as $doc): ?>
                                        <option value="<?php echo $doc['IdMedic']; ?>" <?php echo ($doc['IdMedic'] == $consultation['IdMedic'])? 'selected' : ''; ?>>
                                            <?php echo $doc['Nume'] . ' ' . $doc['Prenume']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Дата</label>
                                <input type="date" name="data" class="form-control" value="<?php echo $consultation['Data']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Время</label>
                                <input type="time" name="time" class="form-control" value="<?php echo $consultation['Ora']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Диагноз</label>
                                <textarea name="diagnostic" class="form-control"><?php echo $consultation['Diagnostic']; ?></textarea>
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
                <h5 class="modal-title">Запланировать консультацию</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Пациент</label>
                        <select name="idPacient" class="form-control" required>
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo $patient['IdPacient']; ?>">
                                    <?php echo $patient['Nume'] . ' ' . $patient['Prenume']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Врач</label>
                        <select name="idMedic" class="form-control" required>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?php echo $doctor['IdMedic']; ?>">
                                    <?php echo $doctor['Nume'] . ' ' . $doctor['Prenume']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Дата</label>
                        <input type="date" name="data" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Время</label>
                        <input type="time" name="ora" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Диагноз</label>
                        <textarea name="diagnostic" class="form-control"></textarea>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary">Запланировать</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>