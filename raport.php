<?php
// File: raport.php
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

<h2>Raport Medical</h2>
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#reportModal">Vezi Raport</button>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Raport Detaliat - Consultatii Medicale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h4>Sumar Pacienți</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Pacient</th>
                            <th>Număr Consultații</th>
                            <th>Total Plătit (RON)</th>
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

                <h4>Statistici Generale</h4>
                <ul>
                    <li><strong>Total consultații:</strong> <?php echo $totalConsultations; ?></li>
                    <li><strong>Sumă medie per pacient:</strong> <?php echo number_format($avgCost, 2); ?> RON</li>
                    <li><strong>Medic cu cele mai multe consultații:</strong> 
                        <?php echo $topDoctor['Nume'] . ' ' . $topDoctor['Prenume'] . ' (' . $topDoctor['consultatii'] . ' consultații)'; ?>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>