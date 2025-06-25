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
('Попеску', 'Ион', '0712345678', 'ion.popescu@gmail.com'),
('Ионеску', 'Мария', '0723456789', 'maria.ionescu@yahoo.com'),
('Георгеску', 'Андрей', '0734567890', 'andrei.georgescu@gmail.com'),
('Стан', 'Елена', '0745678901', 'elena.stan@gmail.com'),
('Думитреску', 'Михай', '0756789012', 'mihai.dumitrescu@yahoo.com'),
('Марин', 'Анна', '0767890123', 'ana.marin@gmail.com'),
('Раду', 'Кристиан', '0778901234', 'cristian.radu@yahoo.com'),
('Добре', 'София', '0789012345', 'sofia.dobre@gmail.com'),
('Георге', 'Влад', '0790123456', 'vlad.gheorghe@gmail.com'),
('Николае', 'Лаура', '0701234567', 'laura.nicolae@yahoo.com');

INSERT INTO Medic (Nume, Prenume, Specializare, TarifConsultatie) VALUES
('Драгу', 'Виктор', 'Терапевт', 150.00),
('Молдован', 'Алина', 'Кардиолог', 200.00),
('Тудор', 'Габриэль', 'Невролог', 180.00),
('Попа', 'Роксана', 'Педиатр', 160.00),
('Лупу', 'Штефан', 'Ортопед', 190.00),
('Гаврила', 'Иоана', 'Дерматолог', 170.00),
('Балан', 'Мариус', 'Офтальмолог', 175.00),
('Стойка', 'Елена', 'Гинеколог', 200.00),
('Чобану', 'Александру', 'Психиатр', 220.00),
('Мунтяну', 'Дана', 'Эндокринолог', 185.00);

INSERT INTO Consultatie (IdPacient, IdMedic, Data, Ora, Diagnostic) VALUES
(1, 1, '2025-06-25', '09:00:00', 'Простуда'),
(2, 2, '2025-06-25', '10:00:00', 'Гипертония'),
(3, 3, '2025-06-26', '11:00:00', 'Мигрень'),
(4, 4, '2025-06-26', '12:00:00', 'Респираторная инфекция'),
(5, 5, '2025-06-27', '13:00:00', 'Растяжение лодыжки'),
(6, 6, '2025-06-27', '14:00:00', 'Атопический дерматит'),
(7, 7, '2025-06-28', '15:00:00', 'Конъюнктивит'),
(8, 8, '2025-06-28', '16:00:00', 'Эндометриоз'),
(9, 9, '2025-06-29', '17:00:00', 'Тревожное расстройство'),
(10, 10, '2025-06-29', '18:00:00', 'Гипотиреоз');