-- MYSQL Database for Online Payroll System
-- IS436 Project Group 7 - Nishan Subba

-- Drop Database if already created
DROP DATABASE IF EXISTS PayrollSystem;

-- Create Database
CREATE DATABASE PayrollSystem;

-- Pick a database to use
USE PayrollSystem;

-- Drop Tables
DROP TABLE IF EXISTS Emp_Repository;
DROP TABLE IF EXISTS Payroll;
DROP TABLE IF EXISTS EmployeeDeduction;
DROP TABLE IF EXISTS Deductions;
DROP TABLE IF EXISTS EmployeeBenefit;
DROP TABLE IF EXISTS Benefits;
DROP TABLE IF EXISTS Timesheet;
DROP TABLE IF EXISTS UserLogin;
DROP TABLE IF EXISTS Employees;
DROP TABLE IF EXISTS Positions;
DROP TABLE IF EXISTS Departments;
DROP TABLE IF EXISTS Addresses;

-- Create Departments Table
CREATE TABLE Departments (
	DepartmentID INT NOT NULL AUTO_INCREMENT,
	DepartmentName VARCHAR(255),
	PRIMARY KEY (DepartmentID)
);


INSERT INTO Departments 
	(DepartmentName)
VALUES
	('Human Resources'),
	('Legal and Compliance'),
	('Operation'),
	('Executive'),
	('IT');

-- Create Positions Table
CREATE TABLE Positions (
	PositionID INT NOT NULL AUTO_INCREMENT,
	PositionTitle VARCHAR(100) NOT NULL,
	DepartmentID INT NOT NULL,
	HourlyRate DECIMAL(10, 2), -- 9999999.99
	PRIMARY KEY (PositionID),
	FOREIGN KEY (DepartmentID)
		REFERENCES Departments (DepartmentID)
);


INSERT INTO Positions
	(PositionTitle, DepartmentID, HourlyRate)
VALUES
	('HR Manager', 1, 50.00),
	('Legal/Compliance Officer', 2, 60.00),
	('Line Manager', 3, 40.00),
	('Product Technician', 5, 30.00),
	('CEO', 4, 200.00),
	('Software Developer', 5, 70.00),
	('IT Manager', 5, 80.00);


-- Create Employees Table
CREATE TABLE Employees (
	EmployeeID INT NOT NULL AUTO_INCREMENT,
	FirstName VARCHAR(255) NOT NULL,
	LastName VARCHAR(255) NOT NULL,
	PositionID INT NOT NULL,
	DepartmentID INT NOT NULL,
	HireDate DATE NOT NULL,
	Role INT NOT NULL, -- 1 = Regular Employee and 0 = Admin
	PRIMARY KEY (EmployeeID),
	FOREIGN KEY (PositionID)
		REFERENCES Positions (PositionID),
	FOREIGN KEY (DepartmentID)
		REFERENCES Departments (DepartmentID)
);


INSERT INTO Employees
	(FirstName, LastName, PositionID, DepartmentID, HireDate, Role)
VALUES
	('John', 'Jackson', 1, 1, DATE '2023-10-20', 0),
	('Robert', 'Cohen', 2, 2, DATE '2023-10-21', 1),
	('Luna', 'Hernandez', 3, 3, DATE '2023-11-20', 1),
	('Max', 'Wattson', 4, 3, DATE '2023-11-20', 1),
	('Fred', 'Jones', 5, 4, DATE '2023-11-20', 0),
	('Melissa', 'May', 6, 5, DATE '2023-11-20', 1),
	('Mark', 'Thomas', 7, 5, DATE '2023-11-20', 1);

-- Create Addresses Table
CREATE TABLE Addresses (
	AddressID INT NOT NULL AUTO_INCREMENT,
	EmployeeID INT NOT NULL,
	StreetAddress VARCHAR(255),
	City VARCHAR(100),
	State VARCHAR(2),
	ZipCode VARCHAR(10),
	PRIMARY KEY (AddressID),
	FOREIGN KEY (EmployeeID)
		REFERENCES Employees (EmployeeID) ON DELETE CASCADE
);


INSERT INTO Addresses
	(EmployeeID, StreetAddress, City, State, ZipCode)
VALUES
	(1, '200 Main St', 'Baltimore', 'MD', '21237'),
	(2, '647 Washington Drive', 'Baltimore', 'MD', '21237'),
	(3, '452 Johnson St', 'Baltimore', 'MD', '21237'),
	(4, '782 Hope Ave', 'Baltimore', 'MD', '21237'),
	(5, '245 Martin Luther Drive', 'Baltimore', 'MD', '21237'),
	(6, '832 Obama St', 'Baltimore', 'MD', '21237'),
	(7, '784 Junior Drive', 'Baltimore', 'MD', '21237');

-- Create UserLogin Table
CREATE TABLE UserLogin (
	UserLoginID INT NOT NULL AUTO_INCREMENT,
	EmployeeID INT NOT NULL,
	Email VARCHAR(255) NOT NULL UNIQUE,
	Phone VARCHAR(10) NOT NULL UNIQUE,
	UserName VARCHAR(100) NOT NULL UNIQUE,
	Password VARCHAR(100) NOT NULL,
	LastLogin TIMESTAMP,
	PRIMARY KEY (UserLoginID),
	FOREIGN KEY (EmployeeID)
		REFERENCES Employees (EmployeeID) ON DELETE CASCADE
);


INSERT INTO UserLogin
	(EmployeeID, Email, Phone, UserName, Password, LastLogin)
VALUES
	(1, 'jjackson1@gmail.com', '4432345465', 'jackson101', 'Jackson@101', NOW()),
	(2, 'rcohen2@gmail.com', '4435637387', 'cohen102', 'Cohen@102', NOW()),
	(3, 'lhernandez3@gmail.com', '4433249876', 'hernandez103', 'Hernandez@103', NOW()),
	(4, 'mwattson4@gmail.com', '4431124309', 'wattson104', 'Wattson@104', NOW()),
	(5, 'fjones5@gmail.com', '4434309999', 'jones105', 'Jones@105', NOW()),
	(6, 'mmay@gmail.com', '4431117777', 'may106', 'May@106', NOW()),
	(7, 'mthomas@gmail.com', '4433438888', 'thomas107', 'Thomas@107', NOW());

-- Create Timesheet Table
CREATE TABLE Timesheet (
	TimesheetID INT NOT NULL AUTO_INCREMENT,
	EmployeeID INT NOT NULL,
	StartTime TIMESTAMP,
	EndTime TIMESTAMP,
	TotalHoursWorked DECIMAL(10, 2),
	OvertimeHours DECIMAL(10, 2),
	PRIMARY KEY (TimesheetID),
	FOREIGN KEY (EmployeeID)
		REFERENCES Employees (EmployeeID) ON DELETE CASCADE
);


INSERT INTO Timesheet
	(EmployeeID, StartTime, EndTime, TotalHoursWorked, OvertimeHours)
VALUES
	(1, TIMESTAMP '2023-10-20 08:00:00', TIMESTAMP '2023-10-20 18:00:00', 10, 4.00),
	(2, TIMESTAMP '2023-10-20 08:00:00', TIMESTAMP '2023-10-20 16:00:00', 8, 0.00),
	(3, TIMESTAMP '2023-10-20 08:00:00', TIMESTAMP '2023-10-20 17:00:00', 9, 0.00),
	(4, TIMESTAMP '2023-10-20 08:00:00', TIMESTAMP '2023-10-20 18:00:00', 10, 4.00),
	( 5, TIMESTAMP '2023-10-20 08:00:00', TIMESTAMP '2023-10-20 18:00:00', 10, 4.00),
	(6, TIMESTAMP '2023-10-20 08:00:00', TIMESTAMP '2023-10-20 18:00:00', 10, 4.00),
	(7, TIMESTAMP '2023-10-20 08:00:00', TIMESTAMP '2023-10-20 18:00:00', 10, 4.00);

-- Create Benefits Table
CREATE TABLE Benefits (
	BenefitID INT NOT NULL AUTO_INCREMENT,
	BenefitName VARCHAR(255),
	BenefitDesciption VARCHAR(255),
	PRIMARY KEY (BenefitID)
);


INSERT INTO Benefits
	(BenefitName, BenefitDesciption)
VALUES
	('Dental', 'Cover 80% of cost'),
	('Health Insurance', 'Cover 70% of cost');

-- Create Employee Benefit Table
CREATE TABLE EmployeeBenefit (
	EmpBenefitID INT NOT NULL AUTO_INCREMENT,
	EmployeeID INT NOT NULL,
	BenefitID INT NOT NULL,
	PRIMARY KEY (EmpBenefitID),
	FOREIGN KEY (EmployeeID)
		REFERENCES Employees (EmployeeID) ON DELETE CASCADE,
	FOREIGN KEY (BenefitID)
		REFERENCES Benefits (BenefitID)
);


INSERT INTO EmployeeBenefit
	(EmployeeID, BenefitID)
VALUES
	(1,1),
	(1, 2),
	(2, 1),
	(3, 2),
	(4, 1),
	(5, 2),
	(6, 2),
	(7, 1);

-- Create Deductions Table
CREATE TABLE Deductions (
	DeductionID INT NOT NULL AUTO_INCREMENT,
	DeductionName VARCHAR(255),
	Amount DECIMAL(10, 2),
	PRIMARY KEY (DeductionID)
);


INSERT INTO Deductions
	(DeductionName, Amount)
VALUES
	('State Tax', 0.10),
	('Federal Tax', 0.05);

-- Create EmployeeDeduction Table
CREATE TABLE EmployeeDeduction (
	EmpDeductionID INT NOT NULL AUTO_INCREMENT,
	EmployeeID INT NOT NULL,
	DeductionID INT NOT NULL,
	PRIMARY KEY (EmpDeductionID),
	FOREIGN KEY (EmployeeID)
		REFERENCES Employees (EmployeeID) ON DELETE CASCADE,
	FOREIGN KEY (DeductionID)
		REFERENCES Deductions (DeductionID)
);


INSERT INTO EmployeeDeduction
	(EmployeeID, DeductionID)
VALUES
	(1,1),
	(1, 2),
	(2, 1),
	(2, 2),
	(3, 1),
	(3, 2),
	(4, 1),
	(4, 2),
	(5, 1),
	(5, 2),
	(6, 1),
	(6, 2),
	(7, 1),
	(7, 2);

-- Create Payroll Table
CREATE TABLE Payroll (
	PayrollID INT NOT NULL AUTO_INCREMENT,
	EmployeeID INT NOT NULL,
	PeriodStartDate DATE,
	PeriodEndDate DATE,
	GrossIncome DECIMAL (10, 2),
	NetIncome DECIMAL (10, 2),
	DateProcessed TIMESTAMP,
	PRIMARY KEY (PayrollID),
	FOREIGN KEY (EmployeeID)
		REFERENCES Employees (EmployeeID) ON DELETE CASCADE
);


-- Create Emp_Repository Table
CREATE TABLE Emp_Repository (
	PastEmpID INT NOT NULL AUTO_INCREMENT,
	FirstName VARCHAR(255) NOT NULL,
	LastName VARCHAR(255) NOT NULL,
	PositionID INT NOT NULL,
	DepartmentID INT NOT NULL,
	StartDate DATE,
	EndDate DATE,
	PRIMARY KEY (PastEmpID),
	FOREIGN KEY (PositionID)
		REFERENCES Positions (PositionID),
	FOREIGN KEY (DepartmentID)
		REFERENCES Departments (DepartmentID)
);


INSERT INTO Emp_Repository
	(FirstName, LastName, PositionID, DepartmentID, StartDate, EndDate)
VALUES
	('Elon', 'Musk', 3, 3, DATE '2023-10-20', DATE '2023-10-21');

COMMIT;

-- Look at the tables
SELECT * FROM Employees;
SELECT * FROM Departments;
SELECT * FROM Positions;
SELECT * FROM Addresses;
SELECT * FROM UserLogin;
SELECT * FROM Benefits;
SELECT * FROM EmployeeBenefit;
SELECT * FROM Deductions;
SELECT * FROM EmployeeDeduction;
SELECT * FROM Timesheet;
SELECT * FROM Payroll;
SELECT * FROM Emp_Repository;


-- Create Procedure HERE
-- Procedure: Add_Employee();

-- Procedures
DROP PROCEDURE IF EXISTS Add_Employee;

DELIMITER //

CREATE PROCEDURE Add_Employee 
(IN v_Fname VARCHAR(255), IN v_Lname VARCHAR(255), IN v_positionID INT, IN v_DeptID INT, 
IN v_HireDate DATE, IN v_Role INT)
BEGIN
	INSERT INTO Employees
		(FirstName, LastName, PositionID, DepartmentID, HireDate, Role)
	VALUES (v_Fname, v_Lname, v_positionID, v_DeptID, v_HireDate, v_Role);
END //

DELIMITER ;

SELECT * FROM Employees;
-- CALL Add_Employee ('First Name', 'Last Name', Position ID, Department ID, 'HIRE DATE', Role);
CALL Add_Employee ('Adam', 'Sandler', 3, 3, '2023-10-20', 1);

SELECT * FROM Employees;

-- Procedure: Add_Employee_Benefits;
DROP PROCEDURE IF EXISTS Add_Employee_Benefits;

DELIMITER //

CREATE PROCEDURE Add_Employee_Benefits (IN v_EmpID INT, IN v_BenefitID INT)
BEGIN
	DECLARE v_EmpID_Count INT;
	DECLARE v_Benefit_Count INT;
	DECLARE v_EmpBenefit_Count INT;
	
	-- Check if employee exist with given Emp ID
	SELECT COUNT(*) INTO v_EmpID_Count
	FROM Employees
	WHERE EmployeeID = v_EmpID;
	
	-- Check if the benefit exists
	SELECT COUNT(*) INTO v_Benefit_Count
	FROM Benefits 
	WHERE BenefitID = v_BenefitID;
	
	-- Check if that employee already has that benefit
	SELECT COUNT(*) INTO v_EmpBenefit_Count
	FROM EmployeeBenefit
	WHERE EmployeeID = v_EmpID
	AND BenefitID = v_BenefitID;
	
	IF v_EmpID_Count = 0 THEN
		SELECT 'Invalid Employee ID, Please try again';
	ELSEIF v_Benefit_Count = 0 THEN
		SELECT 'Invalid Benefit ID, Please try again';
	ELSEIF v_EmpBenefit_Count > 0 THEN
		SELECT 'This employee already has this benefit, cannot add again';
	ELSE
		INSERT INTO EmployeeBenefit
			(EmployeeID, BenefitID)
		VALUES (v_EmpID, v_BenefitID);
	END IF;
END //

DELIMITER ;



-- Procedure: Add_Employee_Benefits;
DROP PROCEDURE IF EXISTS Add_Employee_Deductions;

DELIMITER //

CREATE PROCEDURE Add_Employee_Deductions (IN v_EmpID INT, IN v_DeductionID INT)
BEGIN
	DECLARE v_EmpID_Count INT;
	DECLARE v_Deduction_Count INT;
	DECLARE v_EmpDeduction_Count INT;
	
	-- Check if employee exist with given Emp ID
	SELECT COUNT(*) INTO v_EmpID_Count
	FROM Employees
	WHERE EmployeeID = v_EmpID;
	
	-- Check if the benefit exists
	SELECT COUNT(*) INTO v_Deduction_Count
	FROM Benefits 
	WHERE DeductionID = v_DeductionID;
	
	-- Check if that employee already has that benefit
	SELECT COUNT(*) INTO v_EmpDeduction_Count
	FROM EmployeeBenefit
	WHERE EmployeeID = v_EmpID
	AND DeductionID = v_DeductionID;
	
	IF v_EmpID_Count = 0 THEN
		SELECT 'Invalid Employee ID, Please try again';
	ELSEIF v_Deduction_Count = 0 THEN
		SELECT 'Invalid Deduction ID, Please try again';
	ELSEIF v_EmpDeduction_Count > 0 THEN
		SELECT 'This employee already has this deduction, cannot add again';
	ELSE
		INSERT INTO EmployeeDeduction
		(EmployeeID, DeductionID)
	VALUES (v_EmpID, v_DeductionID);
	END IF;	
END //

DELIMITER ;

-- Procedure: Create_UserLogin;
DROP PROCEDURE IF EXISTS Create_UserLogin;

DELIMITER //

CREATE PROCEDURE Create_UserLogin 
(IN v_EmpID INT, IN v_Email VARCHAR(255), IN v_Phone VARCHAR(10), 
IN v_UserName VARCHAR(100), IN v_Password VARCHAR(100), IN v_LastLogin TIMESTAMP)

BEGIN
	INSERT INTO UserLogin
		(EmployeeID, Email, Phone, UserName, Password, LastLogin)
	VALUES (v_EmpID, v_Email, v_Phone, v_UserName, v_Password, v_LastLogin);
END //

DELIMITER ;

-- Check before calling procedure
SELECT * FROM UserLogin;

CALL Create_UserLogin 
(8, 'asandler108@gmail.com', '4439832345', 'sandler108', 'sandler@108', NOW());

SELECT * FROM UserLogin;

-- Trigger: Generate_PayRoll_Calc
-- Drop trigger if exist
DROP TRIGGER IF EXISTS Generate_PayRoll_Calc;

DELIMITER //

CREATE TRIGGER Generate_PayRoll_Calc
BEFORE INSERT ON Payroll
FOR EACH ROW
BEGIN
    DECLARE v_Calc_Total_Hours DECIMAL(10,2);
	DECLARE v_HourlyRate DECIMAL (10, 2);
	DECLARE v_Total_Deduction DECIMAL (10, 2);
	DECLARE v_OverTime DECIMAL (10, 2);
	
    SELECT SUM(TotalHoursWorked), SUM(OvertimeHours) INTO v_Calc_Total_Hours, v_OverTime
    FROM Timesheet
    WHERE EmployeeID = NEW.EmployeeID
    AND StartTime >= NEW.PeriodStartDate
    AND EndTime <= NEW.PeriodEndDate;

	SELECT p.HourlyRate INTO v_HourlyRate
	FROM Employees e
	JOIN Positions p
	ON e.PositionID = p.PositionID
	WHERE e.EmployeeID = NEW.EmployeeID;

    SET NEW.GrossIncome = (v_Calc_Total_Hours * v_HourlyRate) + (v_OverTime * (v_HourlyRate * 1.5));
    
    -- Calculate Total Deduction
    SELECT SUM(Amount) INTO v_Total_Deduction
    FROM Deductions d
    JOIN EmployeeDeduction ed
    ON d.DeductionID = ed.DeductionID
    JOIN Employees e
    ON e.EmployeeID = ed.EmployeeID
    WHERE e.EmployeeID = NEW.EmployeeID;
    
    SET NEW.NetIncome = NEW.GrossIncome * (1 - v_Total_Deduction);
    
END //

DELIMITER ;

INSERT INTO Payroll
	(EmployeeID, PeriodStartDate, PeriodEndDate, GrossIncome, NetIncome, DateProcessed)
VALUES
	(1, DATE '2023-10-20', DATE '2023-10-21', NULL, NULL, NOW()),
	(2, DATE '2023-10-20', DATE '2023-10-21', NULL, NULL, NOW()),
	(3, DATE '2023-10-20', DATE '2023-10-21', NULL, NULL, NOW()),
	(4, DATE '2023-10-20', DATE '2023-10-21', NULL, NULL, NOW()),
	(5, DATE '2023-10-20', DATE '2023-10-21', NULL, NULL, NOW()),
	(6, DATE '2023-10-20', DATE '2023-10-21', NULL, NULL, NOW()),
	(7, DATE '2023-10-20', DATE '2023-10-21', NULL, NULL, NOW());


SELECT * FROM Payroll;

-- Procedure: Generate_Period_Payroll;
DROP PROCEDURE IF EXISTS Generate_Period_Payroll;

DELIMITER //

CREATE PROCEDURE Generate_Period_Payroll
(IN v_EmpID INT, IN v_StartDate DATE, IN v_EndDate DATE)

BEGIN
	DECLARE v_Fname VARCHAR(255);
	DECLARE v_Lname VARCHAR(255);
	DECLARE v_Position VARCHAR(255);
	DECLARE v_Dept VARCHAR(255);
	DECLARE v_HourlyRate DECIMAL (10, 2);
	DECLARE v_TotalHoursWorked DECIMAL (10, 2);
	DECLARE v_TotalOverTime DECIMAL (10, 2);
	DECLARE v_TotalDeduction DECIMAL (10, 2);
	DECLARE v_GrossPay DECIMAL (10, 2);
	DECLARE v_NetPay DECIMAL (10, 2);
	
	-- Get Emp Name, position, dept
	SELECT FirstName, LastName, PositionTitle, DepartmentName, HourlyRate INTO
	v_Fname, v_Lname, v_Position, v_Dept, v_HourlyRate
	FROM Employees e
	JOIN Positions p
	ON e.PositionID = p.PositionID
	JOIN Departments d
	ON d.DepartmentID = p.DepartmentID
    WHERE e.EmployeeID = v_EmpID;
	
	-- Get Hourly Rate
	
	-- Calculate Total Hours, Overtime worked
	SELECT SUM(TotalHoursWorked), SUM(OvertimeHours) INTO v_TotalHoursWorked, v_TotalOverTime
	FROM Timesheet
	WHERE EmployeeID = v_EmpID
	AND StartTime >= v_StartDate 
	AND EndTime <= v_EndDate;
		
	-- Calculate Total Deduction
	SELECT SUM(Amount) INTO v_TotalDeduction
	FROM Deductions d
	JOIN EmployeeDeduction ed
	ON d.DeductionID = ed.DeductionID
	JOIN Employees e
	ON e.EmployeeID = ed.EmployeeID
	WHERE e.EmployeeID = v_EmpID;
	
	-- Calculate Gross Pay
	SET v_GrossPay = (v_TotalHoursWorked * v_HourlyRate) + (v_TotalOverTime * (1.5 * v_HourlyRate));
	
	-- Calcuate Net Pay
	SET v_NetPay = v_GrossPay * (1 - v_TotalDeduction);
	
	SELECT 
		v_EmpID AS EmployeeID,
		v_Fname AS FirstName,
		v_Lname AS LastName,
		v_Position AS Position,
		v_Dept AS Department,
		v_StartDate AS Period_Start_Date,
		v_EndDate AS Period_End_Date,
		CONCAT('$', v_HourlyRate) AS Hourly_Rate,
		v_TotalHoursWorked AS Total_Regular_Hours,
		v_TotalOverTime AS Total_Overtime_Hours,
		v_TotalDeduction AS Total_Deduction_Percentage,
		CONCAT('$', v_GrossPay) AS Total_Gross_Pay,
		CONCAT('$', v_NetPay) AS Total_Net_Pay;
END //

DELIMITER ;


SELECT * FROM Employees;
SELECT * FROM Timesheet;

CALL Generate_Period_Payroll(1, '2023-10-20', '2023-10-21');

-- Procedure: Remove_Employee
DROP PROCEDURE IF EXISTS Remove_Employee;

DELIMITER //

CREATE PROCEDURE Remove_Employee
(IN v_Fname VARCHAR(255), IN v_Lname VARCHAR(255), IN v_Phone VARCHAR(10))
BEGIN
	DECLARE v_EmpID INT;
	DECLARE v_NameCount INT;
	DECLARE v_PhoneCount INT;
	DECLARE v_EmpCount INT;
	
	-- Variable to check for rows
	DECLARE done INT DEFAULT FALSE;
	
	DECLARE C1 CURSOR FOR
	SELECT e.EmployeeID
	FROM Employees e
	JOIN UserLogin ul
	ON e.EmployeeID = ul.EmployeeID
	WHERE e.FirstName = v_Fname
	AND e.LastName = v_Lname
	AND ul.Phone = v_Phone;
	
	-- SET done to true if no record if found
	DECLARE CONTINUE HANDLER FOR NOT FOUND
	SET done = TRUE;
	
	-- Check if name exist in the employees table
	SELECT COUNT(*) INTO v_NameCount
	FROM Employees
	WHERE FirstName = v_Fname
	AND LastName = v_Lname;
	
	-- Check if phone number exist in the userlogin table
	SELECT COUNT(*) INTO v_PhoneCount
	FROM UserLogin
	WHERE Phone = v_Phone;
	
	-- Check if both name and phone exist
	SELECT COUNT(*) INTO v_EmpCount
	FROM Employees e
	JOIN UserLogin ul
	ON e.EmployeeID = ul.EmployeeID
	WHERE e.FirstName = v_Fname
	AND e.LastName = v_Lname
	AND ul.Phone = v_Phone; 
	
	IF v_NameCount = 0 THEN
		SELECT 'Invalid Name, Please Try Again';
	ELSEIF v_PhoneCount = 0 THEN
		SELECT 'Invalid Phone Number, Please Try Again';
	ELSEIF v_EmpCount = 0 THEN
		SELECT 'Employee Does Not Exist with that name and number';
	ELSE
		OPEN C1;
		read_loop: LOOP
			FETCH C1 INTO v_EmpID;
			IF done THEN
				LEAVE read_loop;
			END IF;
			-- DELETE RECORD
			DELETE FROM Employees
			WHERE EmployeeID = v_EmpID;
			END LOOP;
            SELECT CONCAT(v_Fname, ' ', v_Lname,  ' has been removed') AS Employee_Removed;
            SELECT CONCAT(v_Fname, ' ', v_Lname,  ' has put in Employee Repository Record') AS Employee_Added_Repo_Table;
		CLOSE C1;
	END IF;
END //

DELIMITER ;

-- Drop Trigger if exist
DROP TRIGGER IF EXISTS After_Employee_Delete;

DELIMITER //

CREATE TRIGGER After_Employee_Delete
AFTER DELETE ON Employees
FOR EACH ROW
BEGIN
	INSERT INTO Emp_Repository
		(FirstName, LastName, PositionID, DepartmentID, StartDate, EndDate)
	VALUES (OLD.FirstName, OLD.LastName, OLD.PositionID, OLD.DepartmentID, OLD.HireDate, CURRENT_DATE());
END //

DELIMITER ;

SELECT * FROM Employees;

CALL Remove_Employee ('Max', 'Wattson', '4431124309');
SELECT * FROM Employees;

SELECT * FROM UserLogin;

SELECT * FROM Emp_Repository;


-- Procedure: Show_All_Record
DROP PROCEDURE IF EXISTS Show_All_Employee_Record;

DELIMITER //

CREATE PROCEDURE Show_All_Employee_Record()

BEGIN
	-- Show All Employee Record
SELECT e.EmployeeID, e.FirstName, e.LastName, p.PositionTitle, 
	d.DepartmentName, CONCAT('$',p.HourlyRate) AS Hourly_Rate, e.Role, e.HireDate, a.StreetAddress, 
	a.City, a.State, a.ZipCode, ul.Email, ul.Phone, 
	ul.UserName, ul.LastLogin,
    GROUP_CONCAT(DISTINCT CONCAT(b.BenefitName, ': ', b.BenefitDesciption) SEPARATOR '; ') AS Benefits,
    GROUP_CONCAT(DISTINCT CONCAT(dd.DeductionName, ': ', dd.Amount) SEPARATOR '; ') AS Deductions
	FROM Employees e
	JOIN Positions p
	ON e.PositionID = p.PositionID
	JOIN Departments d
	ON p.DepartmentID = d.DepartmentID
	LEFT JOIN Addresses a
	ON a.EmployeeID = e.EmployeeID
	JOIN UserLogin ul
	ON ul.EmployeeID = e.EmployeeID
	LEFT JOIN EmployeeBenefit eb
	ON eb.EmployeeID = e.EmployeeID
	LEFT JOIN Benefits b
	ON b.BenefitID = eb.BenefitID
	LEFT JOIN EmployeeDeduction ed
	ON ed.EmployeeID = e.EmployeeID
	LEFT JOIN Deductions dd
	ON dd.DeductionID = ed.DeductionID
    GROUP BY e.EmployeeID, e.FirstName, e.LastName, p.PositionTitle, 
    d.DepartmentName, p.HourlyRate, e.HireDate, 
    a.StreetAddress, a.City, a.State, a.ZipCode, 
    ul.Email, ul.Phone, ul.UserName, ul.LastLogin
    ORDER BY e.EmployeeID ASC;

END //

DELIMITER ;

CALL Show_All_Employee_Record();

INSERT INTO Timesheet
	(EmployeeID, StartTime, EndTime, TotalHoursWorked, OvertimeHours)
VALUES
	(1, TIMESTAMP '2023-10-20 08:00:00', TIMESTAMP '2023-10-20 18:00:00', 10, 4.00),
	(1, TIMESTAMP '2023-10-21 08:00:00', TIMESTAMP '2023-10-21 18:00:00', 10, 4.00),
	(1, TIMESTAMP '2023-10-22 08:00:00', TIMESTAMP '2023-10-22 18:00:00', 10, 4.00),
	(1, TIMESTAMP '2023-10-23 08:00:00', TIMESTAMP '2023-10-23 18:00:00', 10, 4.00),
	(1, TIMESTAMP '2023-10-24 08:00:00', TIMESTAMP '2023-10-24 18:00:00', 10, 4.00);

DROP TRIGGER IF EXISTS Calculate_TotalHoursWorked_On_Update;
DELIMITER //
CREATE TRIGGER Calculate_TotalHoursWorked_On_Update
BEFORE UPDATE ON Timesheet
FOR EACH ROW
BEGIN
    IF NEW.EndTime IS NOT NULL AND OLD.StartTime IS NOT NULL THEN
        SET NEW.TotalHoursWorked = TIMESTAMPDIFF(HOUR, OLD.StartTime, NEW.EndTime);
    END IF;
END //
DELIMITER ;


DROP TRIGGER IF EXISTS ReCalculate_TotalHoursWorked_On_Update;
DELIMITER //
CREATE TRIGGER ReCalculate_TotalHoursWorked_On_Update
BEFORE UPDATE ON Timesheet
FOR EACH ROW
BEGIN
    IF NEW.StartTime IS NOT NULL AND NEW.EndTime IS NOT NULL THEN
        SET NEW.TotalHoursWorked = TIMESTAMPDIFF(HOUR, NEW.StartTime, NEW.EndTime);
    END IF;
END //
DELIMITER ;




DROP TRIGGER IF EXISTS Calculate_TotalHoursWorked_On_Insert;
DELIMITER //
CREATE TRIGGER Calculate_TotalHoursWorked_On_Insert
BEFORE INSERT ON Timesheet
FOR EACH ROW
BEGIN
    IF NEW.StartTime IS NOT NULL AND NEW.EndTime IS NOT NULL THEN
        SET NEW.TotalHoursWorked = TIMESTAMPDIFF(HOUR, NEW.StartTime, NEW.EndTime);
    END IF;
END //
DELIMITER ;