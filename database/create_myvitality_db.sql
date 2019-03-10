DROP DATABASE IF EXISTS my_vitality;
CREATE DATABASE IF NOT EXISTS my_vitality;

DROP TABLE IF EXISTS PURCHASE_JOURNAL;
DROP TABLE IF EXISTS RETURN_ITEM;
DROP TABLE IF EXISTS INVOICE_ITEM;
DROP TABLE IF EXISTS SALE;
DROP TABLE IF EXISTS INVOICE;
DROP TABLE IF EXISTS CUS_PHONE;
DROP TABLE IF EXISTS CUSTOMER;
DROP TABLE IF EXISTS EMPLOYEE;
DROP TABLE IF EXISTS SUPPLEMENT_COST;
DROP TABLE IF EXISTS SUPPLEMENT;
DROP TABLE IF EXISTS SUPPLIER_CONTACT;
DROP TABLE IF EXISTS SUPPLIER_CONT_PHONE;
DROP TABLE IF EXISTS SUPPLIER;
DROP TABLE IF EXISTS CUS_REFERENCE;
DROP TABLE IF EXISTS COURIER;
DROP TABLE IF EXISTS JOB;
DROP TABLE IF EXISTS BANK;


CREATE TABLE BANK (
	bankID SMALLINT AUTO_INCREMENT,
	bankName VARCHAR(35) NOT NULL,
	bankBranchCode VARCHAR(6) NOT NULL DEFAULT '000000',
	bankAccountNumber VARCHAR(12) NOT NULL DEFAULT '0000000000',
	bankAccountType VARCHAR(12) NOT NULL DEFAULT 'NONE',
	CONSTRAINT bank_bankID_pk PRIMARY KEY(bankID),
	CONSTRAINT bank_bankAccountType_ck 	CHECK(bankAccountType IN ('CHEQUE', 'CURRENT', 'TRANSMISSION', 'SAVINGS', 'MZANSI', 'NONE'))
);

CREATE TABLE JOB (
	jobID SMALLINT AUTO_INCREMENT,
	jobName VARCHAR(3) NOT NULL, 
	CONSTRAINT job_jobID_pk PRIMARY KEY(jobID),
	CONSTRAINT job_jobName_ck CHECK(jobName IN ('HCP', 'GA', 'SU'))
);


CREATE TABLE COURIER (
	courID SMALLINT AUTO_INCREMENT,
	courName VARCHAR(35) NOT NULL,
	courTel CHAR(18) NOT NULL  DEFAULT '(000)-(000)-(0000)',
	courEmail VARCHAR(50) NOT NULL DEFAULT 'xx@xx.xxx',
	CONSTRAINT courier_courID_pk PRIMARY KEY(courID),
	CONSTRAINT courier_courTel_ck CHECK(courTel LIKE '(---)-(---)-(----)'),
	CONSTRAINT courier_courEmail_ck CHECK(courEmail LIKE '%@%.---' OR courEmail LIKE '%@%.--.--')
);

CREATE TABLE CUS_REFERENCE (
	refID SMALLINT AUTO_INCREMENT,
	refName VARCHAR(30) NOT NULL,
	CONSTRAINT cus_reference_refID_pk PRIMARY KEY(refID)
);


CREATE TABLE SUPPLIER (
	supplierID SMALLINT AUTO_INCREMENT,
	supplierName VARCHAR(35) NOT NULL,
	supplierComments VARCHAR(200) NOT NULL DEFAULT 'NONE',
	bankID SMALLINT,
	CONSTRAINT supplier_supplierID_pk PRIMARY KEY(supplierID),
    CONSTRAINT supplier_bankID_fk FOREIGN KEY (bankID) REFERENCES BANK(bankID)
);


CREATE TABLE SUPPLIER_CONTACT (
	supplierContID SMALLINT AUTO_INCREMENT,
	supplierContName VARCHAR(20) NOT NULL,
	supplierContSurname VARCHAR(20) NOT NULL DEFAULT 'NO SURNAME PROVIDED',
	supplierContEmail VARCHAR(50) NOT NULL DEFAULT 'xx@xx.xxx',
	supplierID SMALLINT,
	CONSTRAINT supplier_contact_supplierContID_pk PRIMARY KEY(supplierContID),
	CONSTRAINT supplier_contact_supplierID_fk FOREIGN KEY(supplierID) REFERENCES SUPPLIER(supplierID),
	CONSTRAINT supplier_contact_supplierContEmail_ck CHECK(supplierContEmail LIKE '%@%.---' OR courEmail LIKE '@%.--.--')
);


CREATE TABLE SUPPLIER_CONT_PHONE (
	suppContPhoID INT AUTO_INCREMENT,
	suppContPhoTel CHAR(18) NOT NULL DEFAULT '(000)-(000)-(0000)',
	suppContPhoType VARCHAR(4) NOT NULL,
	supplierContID SMALLINT,
	CONSTRAINT suppler_cont_phone_suppContPhoID_pk PRIMARY KEY(suppContPhoID),
	CONSTRAINT suppler_cont_phone_supplierContID_fk FOREIGN KEY(supplierContID) REFERENCES SUPPLIER_CONTACT(supplierContID),
	CONSTRAINT suppler_cont_phone_suppContPhoTel_ck CHECK(suppContPhoTel LIKE '(---)-(---)-(----)')
);


CREATE TABLE SUPPLEMENT (
	supID SMALLINT AUTO_INCREMENT,
	supDescription VARCHAR(35) NOT NULL DEFAULT 'NONE',
	supMinLevel INT NOT NULL,
	supStockLevel INT NOT NULL, 
	supStockLevelHeld INT NOT NULL DEFAULT 0, 
	supNappiCode VARCHAR(7) NOT NULL DEFAULT '000000',
	supplierID SMALLINT,
	CONSTRAINT supplement_supID_pk PRIMARY KEY(supID),
	CONSTRAINT supplement_supplierID_fk FOREIGN KEY(supplierID) REFERENCES SUPPLIER(supplierID),
	CONSTRAINT supplement_supMinLevel_ck CHECK(supMinLevel >= 0 AND supMinLevel <= 99999),
	CONSTRAINT supplement_supStockLevel_ck CHECK(supStockLevel >= 0 AND supStockLevel <= 99999),
	CONSTRAINT supplement_supStockLevelHeld_ck CHECK(supStockLevelHeld >= 0 AND supStockLevelHeld <= 99999)
	);

CREATE TABLE SUPPLEMENT_COST (
	supCostID INT AUTO_INCREMENT,
	supCostDate DATE NOT NULL,
	supCostExc DECIMAL(8,2) NOT NULL, 
	supCostInc DECIMAL(8,2) GENERATED ALWAYS AS (supCostExc * 1.14) PERSISTENT,
	supPercInc DECIMAL(8,2) NOT NULL, 
	supClientCost DECIMAL(8,2) GENERATED ALWAYS AS ((supCostExc * 1.14) + supPercInc) PERSISTENT,
	supID SMALLINT,
	CONSTRAINT supplement_cost_supCostID_pk PRIMARY KEY(supCostID),
	CONSTRAINT supplement_cost_supID_fk FOREIGN KEY(supID) REFERENCES SUPPLEMENT(supID),
	CONSTRAINT supplement_cost_supCostExc_ck CHECK(supCostExc >= 0.00),
	CONSTRAINT supplement_cost_supCostInc_ck CHECK(supCostInc >= 0.00),
	CONSTRAINT supplement_cost_supPercInc_ck CHECK(supPercInc >= 0.00),
	CONSTRAINT supplement_cost_supClientCost_ck CHECK(supClientCost >= 0.00)
);

CREATE TABLE EMPLOYEE (
	empID INT AUTO_INCREMENT,
	empTitle VARCHAR(10) NOT NULL,
	empName VARCHAR(20) NOT NULL,
	empSurname VARCHAR(20) NOT NULL,
	empAddressLine1 VARCHAR(40) NOT NULL DEFAULT 'NONE',
	empAddressLine2 VARCHAR(40) NOT NULL DEFAULT 'NONE',
	empAddressLine3 VARCHAR(40) NOT NULL DEFAULT 'NONE',
	empAddressLine4 VARCHAR(40) NOT NULL DEFAULT 'NONE',
	empPostCode CHAR(4) NOT NULL DEFAULT '0000',
	empTel CHAR(18) NOT NULL DEFAULT '(000)-(000)-(0000)',
	EmpCompanyEmail VARCHAR(50) NOT NULL DEFAULT 'xx@xx.xxx',
	jobID SMALLINT,
	CONSTRAINT employee_empID_pk PRIMARY KEY(empID),
	CONSTRAINT employee_jobID_fk FOREIGN KEY(jobID) REFERENCES JOB(jobID),
	CONSTRAINT employee_courTel_ck CHECK(courTel LIKE '(---)-(---)-(----)'),
	CONSTRAINT employee_courEmail_ck CHECK(courEmail LIKE '%@%.---' OR courEmail LIKE '%@%.--.--')
);


-- The majority of customerID's have a length of 13. Some records contain 11 and 12 characters
-- Data uploaded as BIGINT to allow for the large integer value needed to store this large number
-- auto increment used to generate the number, the number will not go below the last number created, so will not drop down to an 11 or 12 digit number
-- there is 88,909,205,916 vacant numbers before the last order and the limit for 13 length digit.  
-- From MariaDB 10.2.6 auto_increment columns are no longer permitted in CHECK constraints, DEFAULT value expressions and virtual columns
-- above line from - https://mariadb.com/kb/en/library/auto_increment/#check-constraints-default-values-and-virtual-columns
-- if neccessary for a 13 length cusID, include in appliaction code 
CREATE TABLE CUSTOMER (
	cusID BIGINT AUTO_INCREMENT,
	cusName VARCHAR(20) NOT NULL,
	cusSurname VARCHAR(20) NOT NULL,
	cusHomeTel CHAR(18) NOT NULL DEFAULT '(000)-(000)-(0000)',
	cusWorkTel CHAR(18) NOT NULL DEFAULT '(000)-(000)-(0000)',
	cusCel CHAR(18) NOT NULL DEFAULT '(000)-(000)-(0000)',
	refID SMALLINT DEFAULT 7,
	CONSTRAINT customer_cusID_pk PRIMARY KEY(cusID),
	CONSTRAINT customer_refID_fk FOREIGN KEY(refID) REFERENCES CUS_REFERENCE(refID),
	CONSTRAINT customer_cusHomeTel_ck CHECK(cusHomeTel LIKE '(---)-(---)-(----)'),
	CONSTRAINT customer_cusWorkTel_ck CHECK(cusWorkTel LIKE '(---)-(---)-(----)'),
	CONSTRAINT customer_cusCel_ck CHECK(cusCel LIKE '(---)-(---)-(----)')
);


CREATE TABLE CUS_ADDRESS (
	cusAddressID INT AUTO_INCREMENT,
	cusAddressLine1 VARCHAR(40) NOT NULL,
	cusAddressLine2 VARCHAR(40) NOT NULL DEFAULT 'NONE',
	cusAddressLine3 VARCHAR(40) NOT NULL DEFAULT 'NONE',
	cusAddressLine4 VARCHAR(40) NOT NULL DEFAULT 'NONE',
	cusPostCode CHAR(4) NOT NULL DEFAULT '0000',
	cusID BIGINT,
	CONSTRAINT cus_address_cusAddressID_pk PRIMARY KEY(cusAddressID),
	CONSTRAINT cus_address_cusID_fk FOREIGN KEY(cusID) REFERENCES CUSTOMER(cusID)
);


CREATE TABLE CUS_EMAIL (
	cusEmailID INT AUTO_INCREMENT,
	cusEmailAddress VARCHAR(50) NOT NULL DEFAULT 'xx@xx.xxx',
	cusID BIGINT,
	CONSTRAINT cusEmail_cusEmailID_pk PRIMARY KEY(cusEmailID),
	CONSTRAINT cusEmail_cusID_fk FOREIGN KEY(cusID) REFERENCES CUSTOMER(cusID),
	CONSTRAINT cusEmail_cusEmailAddress_ck CHECK(cusEmailAddress LIKE '%@%.---' OR cusEmailAddress LIKE '%@%.--.--')
);
	

CREATE TABLE INVOICE (
	invID INT AUTO_INCREMENT,    
	invDate DATE NOT NULL,
	invTotalCost DECIMAL(9,2) NOT NULL,
	bankID SMALLINT,
	cusID BIGINT,
	empID INT,
	CONSTRAINT invoice_invID_pk PRIMARY KEY(invID),
	CONSTRAINT invoice_bankID_fk FOREIGN KEY(bankID) REFERENCES BANK(bankID),
	CONSTRAINT invoice_cusID_fk FOREIGN KEY(cusID) REFERENCES CUSTOMER(cusID),
	CONSTRAINT invoice_empID_fk FOREIGN KEY(empID) REFERENCES EMPLOYEE(empID),
	CONSTRAINT invoice_invTotalCost_ck CHECK(invTotalCost >= 0.00)
);


CREATE TABLE SALE (
	saleID INT AUTO_INCREMENT,
	salePaymentAmt DECIMAL(9,2) NOT NULL DEFAULT 0.00,
	salePaymentDate DATE NOT NULL,
	saleStatus VARCHAR(8) NOT NULL DEFAULT 'PENDING',
	invID INT,
	CONSTRAINT sale_saleID_pk PRIMARY KEY(saleID),
	CONSTRAINT sale_invID_fk FOREIGN KEY(invID) REFERENCES INVOICE(invID),
	CONSTRAINT sale_salePaymentAmt_ck CHECK(salePaymentAmt >= 0.00),
	CONSTRAINT sale_salePaymentDate_ck CHECK(salePaymentDate >= invDate),
	CONSTRAINT sale_saleStatus_ck CHECK(saleStatus IN ('APPROVED', 'CANCELED', 'SHIPPED', 'HISTORIC', 'PENDING'))
);


CREATE TABLE INVOICE_ITEM  (
	itmID INT AUTO_INCREMENT,
	itmQty INT NOT NULL,
	itmSoldPrice DECIMAL(9,2) NOT NULL,
	itmTotalPrice DECIMAL(9,2) GENERATED ALWAYS AS (itmQty * itmSoldPrice) PERSISTENT,
	supID SMALLINT,
	invID INT,
	CONSTRAINT invoice_item_itmID_pk PRIMARY KEY(itmID),
	CONSTRAINT invoice_item_supID_fk FOREIGN KEY(supID) REFERENCES SUPPLEMENT(supID),
	CONSTRAINT invoice_item_invID_fk FOREIGN KEY(invID) REFERENCES INVOICE(invID), 
	CONSTRAINT invoice_item_itmQty_ck CHECK(itmQty >= 0 AND itmQty <= 99999),
	CONSTRAINT invoice_item_itmSoldPrice_ck CHECK(itmSoldPrice >= 0.00),
	CONSTRAINT invoice_item_itmTotalPrice_ck CHECK(itmTotalPrice >= 0.00)
);


CREATE TABLE RETURN_ITEM  (
	rtnItemID INT AUTO_INCREMENT,
	rtnItemDate DATE NOT NULL,
	rtnItemQty INT NOT NULL,
	rtnItemReason VARCHAR(50) NOT NULL DEFAULT 'NONE',
	rtnItemCondition BOOLEAN NOT NULL DEFAULT FALSE,
	itmID INT,
	CONSTRAINT return_item_rtnItemID_pk PRIMARY KEY(rtnItemID),
	CONSTRAINT return_item_itmID_fk FOREIGN KEY(itmID) REFERENCES INVOICE_ITEM(itmID),
	CONSTRAINT return_item_rtnItemQty_ck CHECK (rtnItemQty >= 0 AND rtnItemQty <= 99999)
);


CREATE TABLE PURCHASE_JOURNAL (
	pjID INT AUTO_INCREMENT,
	pjPurchaseDate DATE NOT NULL,
	pjCostExc DECIMAL(8,2) NOT NULL, 
	pjCostInc DECIMAL(8,2) GENERATED ALWAYS AS (pjCostExc * 1.14) PERSISTENT, 
	pjQty INT NOT NULL, 
	supplierID SMALLINT,
	supID SMALLINT,
	CONSTRAINT purchase_journal_pjID_pk PRIMARY KEY(pjID),
	CONSTRAINT purchase_journal_supplierID_fk FOREIGN KEY(supplierID) REFERENCES SUPPLIER(supplierID),
	CONSTRAINT purchase_journal_supID_fk FOREIGN KEY(supID) REFERENCES SUPPLEMENT(supID),
	CONSTRAINT purchase_journal_pjCostExc_ck CHECK(pjCostExc >= 0.00),
	CONSTRAINT purchase_journal_pjCostInc_ck CHECK(pjCostInc >= 0.00),
	CONSTRAINT purchase_journal_pjQty_ck CHECK (pjQty >= 0 AND pjQty <= 99999)
);


CREATE TABLE SHIPMENT (
	shipID INT AUTO_INCREMENT,
	shipDateSent DATE NOT NULL,
	courID SMALLINT,
	saleID INT,
	CONSTRAINT shipment_shipID_pk PRIMARY KEY(shipID),
	CONSTRAINT shipment_courID_fk FOREIGN KEY(courID) REFERENCES COURIER(courID),
	CONSTRAINT shipment_saleID_fk FOREIGN KEY(saleID) REFERENCES SALE(saleID)
);

