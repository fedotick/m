CREATE DATABASE medical_db;
USE medical_db;

CREATE TABLE Pacient (
    IdPacient INT AUTO_INCREMENT PRIMARY KEY,
    Nume VARCHAR(50) NOT NULL,
    Prenume VARCHAR(50) NOT NULL,
    Telefon VARCHAR(15) NOT NULL,
    Email VARCHAR(100) NOT NULL
);

CREATE TABLE Medic (
    IdMedic INT AUTO_INCREMENT PRIMARY KEY,
    Nume VARCHAR(50) NOT NULL,
    Prenume VARCHAR(50) NOT NULL,
    Specializare VARCHAR(50) NOT NULL,
    TarifConsultatie DECIMAL(10,2) NOT NULL
);

CREATE TABLE Consultatie (
    IdConsultatie INT AUTO_INCREMENT PRIMARY KEY,
    IdPacient INT NOT NULL,
    IdMedic INT NOT NULL,
    Data DATE NOT NULL,
    Ora TIME NOT NULL,
    Diagnostic VARCHAR(255),
    FOREIGN KEY (IdPacient) REFERENCES Pacient(IdPacient),
    FOREIGN KEY (IdMedic) REFERENCES Medic(IdMedic)
);

-- Insert sample data
INSERT INTO Pacient (Nume, Prenume, Telefon, Email) VALUES
('Popescu', 'Ion', '0712345678', 'ion.popescu@gmail.com'),
('Ionescu', 'Maria', '0723456789', 'maria.ionescu@yahoo.com'),
('Georgescu', 'Andrei', '0734567890', 'andrei.georgescu@gmail.com'),
('Stan', 'Elena', '0745678901', 'elena.stan@gmail.com'),
('Dumitrescu', 'Mihai', '0756789012', 'mihai.dumitrescu@yahoo.com'),
('Marin', 'Ana', '0767890123', 'ana.marin@gmail.com'),
('Radu', 'Cristian', '0778901234', 'cristian.radu@yahoo.com'),
('Dobre', 'Sofia', '0789012345', 'sofia.dobre@gmail.com'),
('Gheorghe', 'Vlad', '0790123456', 'vlad.gheorghe@gmail.com'),
('Nicolae', 'Laura', '0701234567', 'laura.nicolae@yahoo.com');

INSERT INTO Medic (Nume, Prenume, Specializare, TarifConsultatie) VALUES
('Dragu', 'Victor', 'Terapeut', 150.00),
('Moldovan', 'Alina', 'Cardiolog', 200.00),
('Tudor', 'Gabriel', 'Neurolog', 180.00),
('Popa', 'Roxana', 'Pediatru', 160.00),
('Lupu', 'Stefan', 'Ortoped', 190.00),
('Gavrila', 'Ioana', 'Dermatolog', 170.00),
('Balan', 'Marius', 'Oftalmolog', 175.00),
('Stoica', 'Elena', 'Ginecolog', 200.00),
('Ciobanu', 'Alexandru', 'Psihiatru', 220.00),
('Munteanu', 'Dana', 'Endocrinolog', 185.00);

INSERT INTO Consultatie (IdPacient, IdMedic, Data, Ora, Diagnostic) VALUES
(1, 1, '2025-06-25', '09:00:00', 'Gripă comună'),
(2, 2, '2025-06-25', '10:00:00', 'Hipertensiune arterială'),
(3, 3, '2025-06-26', '11:00:00', 'Migrenă'),
(4, 4, '2025-06-26', '12:00:00', 'Infecție respiratorie'),
(5, 5, '2025-06-27', '13:00:00', 'Entorsă gleznă'),
(6, 6, '2025-06-27', '14:00:00', 'Dermatită atopică'),
(7, 7, '2025-06-28', '15:00:00', 'Conjunctivită'),
(8, 8, '2025-06-28', '16:00:00', 'Endometrioza'),
(9, 9, '2025-06-29', '17:00:00', 'Tulburare anxioasă'),
(10, 10, '2025-06-29', '18:00:00', 'Hipotiroidism');