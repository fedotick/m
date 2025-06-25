<?php
include 'includes/db.php';
include 'includes/header.php';

// Fetch report data
$patientSummary = $pdo->query("
    SELECT p.IdPacient, p.Nume, p.Prenume, COUNT(c.IdConsultatie) as consultatii, SUM(m.TarifConsultatie) as total
    FROM Pacient p
    LEFT JOIN Consultatie c ON p.IdPacient = c.IdPacient
    LEFT JOIN Medic m ON c.IdMedic = m.IdMedic
    GROUP BY p.IdPacient
")->fetchAll(PDO::FETCH_ASSOC);

$totalConsultations = $pdo->query("SELECT COUNT(*) FROM Consultatie")->fetchColumn();
$avgCost = $pdo->query("
    SELECT AVG(total) 
    FROM (
        SELECT SUM(m.TarifConsultatie) as total
        FROM Pacient p
        LEFT JOIN Consultatie c ON p.IdPacient = c.IdPacient
        LEFT JOIN Medic m ON c.IdMedic = m.IdMedic
        GROUP BY p.IdPacient
        HAVING total > 0
    ) t
")->fetchColumn();

$topDoctor = $pdo->query("
    SELECT m.Nume, m.Prenume, COUNT(c.IdConsultatie) as consultatii
    FROM Medic m
    LEFT JOIN Consultatie c ON m.IdMedic = c.IdMedic
    GROUP BY m.IdMedic
    ORDER BY consultatii DESC
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);
?>

<h2>Медицинский отчет</h2>
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#reportModal">Посмотреть отчет</button>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подробный отчет - Медицинские консультации</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h4>Сводка по пациентам</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Пациент</th>
                            <th>Количество консультаций</th>
                            <th>Общая сумма (RUB)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patientSummary as $row): ?>
                            <tr>
                                <td><?php echo $row['Nume'] . ' ' . $row['Prenume']; ?></td>
                                <td><?php echo $row['consultatii']; ?></td>
                                <td><?php echo $row['total'] ?: '0.00'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h4>Общая статистика</h4>
                <ul>
                    <li><strong>Всего консультаций:</strong> <?php echo $totalConsultations; ?></li>
                    <li><strong>Средняя сумма на пациента:</strong> <?php echo number_format($avgCost, 2); ?> RUB</li>
                    <li><strong>Врач с наибольшим количеством консультаций:</strong> 
                        <?php echo $topDoctor['Nume'] . ' ' . $topDoctor['Prenume'] . ' (' . $topDoctor['consultatii'] . ' консультаций)'; ?>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>