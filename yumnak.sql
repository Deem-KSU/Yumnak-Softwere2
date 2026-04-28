-- Create and use the database
CREATE DATABASE IF NOT EXISTS YumnakDB;
USE YumnakDB;

-- Independent Tables
CREATE TABLE TRAVELER (
    UserID INT(11) AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(100) UNIQUE NOT NULL,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Phone VARCHAR(20) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    DOB DATE
);

CREATE TABLE ADMIN (
    AdminID INT(11) AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(100) UNIQUE NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Phone VARCHAR(20) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    DOB DATE
);

CREATE TABLE AIRPORT (
    AirportID INT(11) AUTO_INCREMENT PRIMARY KEY,
    AirportName VARCHAR(150) NOT NULL,
    City VARCHAR(100) NOT NULL,
    ImagePath VARCHAR(255) NOT NULL
);

CREATE TABLE ASSISTANCE_TYPE (
    AssistanceTypeID INT(11) AUTO_INCREMENT PRIMARY KEY,
    AssistanceName VARCHAR(100) NOT NULL,
    Description VARCHAR(255),
    Price DECIMAL(10, 2) DEFAULT 0.00 CHECK (Price >= 0.00)
);

-- Dependent Tables 
-- 1st level
CREATE TABLE ASSISTANT (
    AssistantID INT(11) AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Phone VARCHAR(20) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Specialization ENUM(
        'Wheelchair Assistance', 
        'Mobility Assistance', 
        'Visual Impairment Assistance', 
        'Hearing Impairment Assistance', 
        'Cognitive Assistance'
    ) NOT NULL,
    AdminID INT,
    FOREIGN KEY (AdminID) REFERENCES ADMIN(AdminID) ON DELETE SET NULL
);

CREATE TABLE GATE (
    GateID VARCHAR(50) PRIMARY KEY,
    AirportID INT,
    FOREIGN KEY (AirportID) REFERENCES AIRPORT(AirportID) ON DELETE CASCADE
);

-- 2nd Level

CREATE TABLE ASSISTANCE_REQUEST (
    RequestID INT(11) AUTO_INCREMENT PRIMARY KEY,
    PreferredTime DATETIME NOT NULL,
    Date DATETIME DEFAULT CURRENT_TIMESTAMP,
    ExtraNote VARCHAR(255),
    Status ENUM('Pending', 'Accepted', 'Rejected', 'Cancelled', 'Completed') DEFAULT 'Pending', 
    IsPaid BOOLEAN DEFAULT FALSE,
    TravelerID INT NOT NULL,
    AdminID INT,
    AssistantID INT,
    GateID VARCHAR(50) NOT NULL,
    FOREIGN KEY (TravelerID) REFERENCES TRAVELER(UserID) ON DELETE CASCADE,
    FOREIGN KEY (AdminID) REFERENCES ADMIN(AdminID) ON DELETE SET NULL,
    FOREIGN KEY (AssistantID) REFERENCES ASSISTANT(AssistantID) ON DELETE SET NULL,
    FOREIGN KEY (GateID) REFERENCES GATE(GateID) ON DELETE RESTRICT
);

-- 3rd Level

CREATE TABLE REQUEST_TYPE (
    AssistanceTypeID INT,
    RequestID INT,
    PRIMARY KEY (AssistanceTypeID, RequestID),
    FOREIGN KEY (AssistanceTypeID) REFERENCES ASSISTANCE_TYPE(AssistanceTypeID) ON DELETE CASCADE,
    FOREIGN KEY (RequestID) REFERENCES ASSISTANCE_REQUEST(RequestID) ON DELETE CASCADE
);

CREATE TABLE REVIEW (
    ReviewID INT AUTO_INCREMENT PRIMARY KEY,
    Stars INT CHECK (Stars >= 1 AND Stars <= 5),
    Comment VARCHAR(255),
    Date DATETIME DEFAULT CURRENT_TIMESTAMP,
    RequestID INT NOT NULL,
    FOREIGN KEY (RequestID) REFERENCES ASSISTANCE_REQUEST(RequestID) ON DELETE CASCADE
);

-- ==========================================
-- INSERTION 
-- ==========================================

INSERT INTO TRAVELER (UserName, Email, Phone, Password, DOB) VALUES
('Tariq Alotaibi', 'tariq@gmail.com', '+966501112222', '$2a$12$GzbsYcvFMRrlKnwwmQsx/OOMX.U0z4d7ccA5Ouzr.P0B7.H5DQ.J2', '1980-05-10'),
('Sara Alsaud', 'sara@gmail.com', '+966503334444', '$2a$12$upKRTcM3PjHGcYplcJ5hR.58z3tm2anGi.jhRdx.5A.2jotJ3EQX6', '1992-08-15'),
('Majed Alharbi', 'majed@gmail.com', '+966505556666', '$2a$12$WU65E2Rh4Bb7KbrQAyYsBexJcuaYyC8JkRqFYxu2rGp2opnr1jVMu', '1975-12-01'),
('Reem Alqahtani', 'reem@gmail.com', '+966507778888', '$2a$12$C0ruSsEHe.TzAzK9.adhA.AhWoiIPoi2eOhj1VdVAD1zLPCaFrEjy', '2000-03-22');

-- Insert 3 Admins
INSERT INTO ADMIN (UserName, Email, Phone, Password, DOB) VALUES
('Khalid Abdullah', 'khalid.admin@yumnak.com', '+966555123456', '$2a$12$9xBAvl0GP0Ni9hBxcwwy7OO32gQ56Y1opaTP32pGZ8M0BGaBqcTOS', '1988-02-20');

-- Insert All 28 Saudi Airports
INSERT INTO AIRPORT (AirportName, City, ImagePath) VALUES
('King Khalid International Airport', 'Riyadh', 'Image/Riyadh.jpg'),
('King Abdulaziz International Airport', 'Jeddah', 'Image/Jeddah.jpg'),
('King Fahd International Airport', 'Dammam', 'Image/Dammam.jpg'),
('Prince Mohammad bin Abdulaziz International Airport', 'Medina', 'Image/Medina.jpg'),
('Taif International Airport', 'Taif', 'Image/Taif.jpg'),
('Abha International Airport', 'Abha', 'Image/Abha.jpg'),
('King Abdullah bin Abdulaziz International Airport', 'Jizan', 'Image/Jizan.jpg'),
('Prince Nayef bin Abdulaziz International Airport', 'Al Qassim', 'Image/Al Qassim.jpg'),
('Hail International Airport', 'Hail', 'Image/Hail.jpg'),
('Al-Ahsa International Airport', 'Al-Ahsa', 'Image/Al-Ahsa.jpg'),
('Al-Jouf International Airport', 'Al-Jouf', 'Image/Al-Jouf.jpg'),
('Prince Abdulmohsin bin Abdulaziz International Airport', 'Yanbu', 'Image/Yanbu.jpg'),
('Al-Ula International Airport', 'Al-Ula', 'Image/Al-Ula.jpg'),
('Neom Bay Airport', 'Neom', 'Image/Neom.jpg'),
('Red Sea International Airport', 'Red Sea Project', 'Image/Red Sea Project.jpg'),
('Prince Sultan bin Abdulaziz Airport', 'Tabuk', 'Image/Tabuk.jpg'),
('King Saud Local Airport', 'Al Bahah', 'Image/Al Bahah.jpg'),
('Najran Domestic Airport', 'Najran', 'Image/Najran.jpg'),
('Bisha Domestic Airport', 'Bisha', 'Image/Bisha.jpg'),
('Arar Domestic Airport', 'Arar', 'Image/Arar.jpg'),
('Gurayat Domestic Airport', 'Gurayat', 'Image/Gurayat.jpg'),
('Turaif Domestic Airport', 'Turaif', 'Image/Turaif.jpg'),
('Rafha Domestic Airport', 'Rafha', 'Image/Rafha.jpg'),
('Al Qaisumah/Hafr Al Batin Airport', 'Hafar Al-Batin', 'Image/Hafar Al-Batin.jpg'),
('Wadi al-Dawasir Domestic Airport', 'Wadi al-Dawasir', 'Image/Wadi al-Dawasir.jpg'),
('Dawadmi Domestic Airport', 'Dawadmi', 'Image/Dawadmi.jpg'),
('Sharurah Domestic Airport', 'Sharurah', 'Image/Sharurah.jpg'),
('Al Wajh Domestic Airport', 'Al Wajh', 'Image/Al Wajh.jpg');

-- Insert 3 Assistance Types
INSERT INTO ASSISTANCE_TYPE (AssistanceName, Description, Price) VALUES
('Wheelchair Assistance', 'Full wheelchair support from the entrance gate directly to the aircraft seat.', 50.00),
('Mobility Assistance', 'Walking support and luggage handling for individuals who cannot stand for long periods.', 40.00),
('Visual Impairment Assistance', 'Dedicated spatial guidance and navigation support through the airport.', 60.00),
('Hearing Impairment Assistance', 'Visual cue navigation and sign language communication support.', 55.00),
('Cognitive Assistance', 'Specialized, patient accompaniment for travelers with cognitive disabilities or autism.', 65.00);

-- Insert 3 Assistants (Managed by Admin 1)
INSERT INTO ASSISTANT (Name, Phone, Email, Specialization, AdminID) VALUES
('Abdullah Yasser', '+966544111222', 'abdullah@yumnak.com', 'Wheelchair Assistance', 1),
('Fatima Saad', '+966544333444', 'fatima@yumnak.com', 'Visual Impairment Assistance', 1),
('Yazeed Ali', '+966544555666', 'yazeed@yumnak.com', 'Hearing Impairment Assistance', 1),
('Maha Sultan', '+966544777888', 'maha@yumnak.com', 'Mobility Assistance', 1),
('Saud Ibrahim', '+966544999000', 'saud@yumnak.com', 'Cognitive Assistance', 1);

-- Insert Gates for all 29 Airports
INSERT INTO GATE (GateID, AirportID) VALUES
-- Riyadh (AirportID 1)
('RU12', 1), ('RU15', 1), ('RU22', 1),
-- Jeddah (AirportID 2)
('JE05', 2), ('JE14', 2), ('JE28', 2),
-- Dammam (AirportID 3)
('DM08', 3), ('DM11', 3), 
-- Medina (AirportID 4)
('ME02', 4), ('ME05', 4),
-- 1 Default Gate for the other 25 airports (To prevent UI crashes)
('TA01', 5), ('AB01', 6), ('JI01', 7), ('QA01', 8), ('HA01', 9), 
('AH01', 10), ('JO01', 11), ('YA01', 12), ('UL01', 13), ('NE01', 14), 
('RS01', 15), ('TB01', 16), ('BH01', 17), ('NJ01', 18), ('BI01', 19), 
('AR01', 20), ('GU01', 21), ('TU01', 22), ('RA01', 23), ('HB01', 24), 
('WD01', 25), ('DA01', 26), ('SH01', 27), ('WJ01', 28);

-- Insert 5 Requests
INSERT INTO ASSISTANCE_REQUEST (PreferredTime, ExtraNote, Status, IsPaid, TravelerID, AdminID, AssistantID, GateID) VALUES
('2026-05-20 14:00:00', 'Requires assistance from check-in to boarding gate.', 'Pending', TRUE, 1, 1, NULL, 'RU12'),
('2026-05-22 10:30:00', 'Passenger arriving early.', 'Accepted', TRUE, 2, 1, 1, 'JE05'),
('2026-04-15 08:00:00', 'Needs help with 2 heavy bags.', 'Completed', TRUE, 3, 1, 3, 'DM08'),
('2026-06-01 18:45:00', 'Flight was changed.', 'Cancelled', TRUE, 1, 1, NULL, 'RU12'),
('2026-05-25 23:00:00', 'Last minute request.', 'Rejected', FALSE, 2, 1, NULL, 'JE05');

-- Insert 5 Request_Types (Linking the requests to their required assistance)
INSERT INTO REQUEST_TYPE (AssistanceTypeID, RequestID) VALUES
(1, 1), -- Request 1 needs Wheelchair
(2, 1), -- Request 1 needs Visual Guidance
(3, 3), -- Request 3 needs Hearing Assistance
(2, 4), -- Request 4 needs Visual Guidance
(3, 5); -- Request 5 needs Sign Language Support

-- Insert 3 Reviews (Usually only done for Completed or Cancelled trips)
INSERT INTO REVIEW (Stars, Comment, Date, RequestID) VALUES
(5, 'Fatima was an excellent guide, very respectful and clear. Made my airport experience completely stress-free!', '2026-04-21 14:30:00', 3),
(5, 'Amazing service! The wheelchair assistance was prompt and the staff was extremely kind. Highly recommend Yumnak.', '2026-04-23 09:15:00', 1),
(4, 'Very easy to book through the system and my request was accepted very quickly. Looking forward to my flight!', '2026-05-18 11:45:00', 2),
(5, 'BEST TEAM !! All the love to Yumnak', '2026-04-22 19:45:00', 5);