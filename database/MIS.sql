-- most commands are grouped into two sections
-- first section is for the MIS dashboard which gives a summary of data
-- second section is for the extended version of the dash board where the user can get a detailed view of the data
-- dashboard provides reports relating to stock, sales, financial and pricing data

-- unless otherwise stated, the yearly dates reflect financial years - 01 March to 29 Feb
-- http://www.sars.gov.za/ClientSegments/Individuals/Need-Help/Pages/Calender.aspx
-- when using yearly data, application program can replace integer values and loop through the same command using counters and parameter values


-- Commands relating to Stock Information
---------------------------------------------------------------------------------------------------------------------------------------------
-- MIS dashboard
-- get a list of supplements and its supplier that should be ordered due to low stock levels
SELECT supID, supplierName
FROM SUPPLEMENT JOIN SUPPLIER USING(supplierID)
WHERE supStockLevel <= supMinLevel;

-- Detailed view
-- get the contact details for the suppliers of low stock items
SELECT DISTINCT supplierName, supplierContName, supplierContSurname, supplierContEmail, suppContPhoTel, suppContPhoType
FROM SUPPLEMENT JOIN SUPPLIER USING(supplierID) 
	JOIN SUPPLIER_CONTACT USING(supplierID) 
	JOIN SUPPLIER_CONT_PHONE USING(supplierContID)
WHERE supStockLevel <= supMinLevel;

-- End of commands relating to Stock Information

-- Commands relating to sales information
---------------------------------------------------------------------------------------------------------------------------------------------
-- MIS dashboard
-- summary of supplements sold during the last month
SELECT supID, SUM(itmQty) AS quantity 
FROM INVOICE_ITEM JOIN INVOICE USING(invID)
WHERE invDate >=  DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
GROUP BY supID;

-- Detailed view
-- summary of supplements sold during previous financial years
SELECT supID, SUM(itmQty) AS quantity 
FROM INVOICE_ITEM JOIN INVOICE USING(invID)
WHERE invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE
GROUP BY supID;

SELECT supID, SUM(itmQty) AS quantity 
FROM INVOICE_ITEM JOIN INVOICE USING(invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29')
GROUP BY supID;

SELECT supID, SUM(itmQty) AS quantity 
FROM INVOICE_ITEM JOIN INVOICE USING(invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29')
GROUP BY supID;

SELECT supID, SUM(itmQty) AS quantity 
FROM INVOICE_ITEM JOIN INVOICE USING(invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29')
GROUP BY supID;

SELECT supID, SUM(itmQty) AS quantity 
FROM INVOICE_ITEM JOIN INVOICE USING(invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29')
GROUP BY supID;


---------------------------------------------------------------------------------------------------------------------------------------------
-- MIS dashboard
-- list of top ten supplements sold during the last month
-- query returns top ten values, not top ten rows
-- subquery returns the 10th value, using the offset parameter to the limit clause
-- parent query uses value to retrieve all rows equal too or above that value
SELECT supID, SUM(itmQty) AS quantity 
FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
WHERE invDate >=  DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
GROUP BY supID 
HAVING quantity >= (
	SELECT SUM(itmQty) AS quantity 
    FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
	WHERE invDate >=  DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
    GROUP BY supID 
	ORDER BY quantity  DESC LIMIT 9,1
	)
ORDER BY quantity DESC;

-- Detailed view
-- list of top ten supplements sold during previous financial years
-- query returns top ten values, not top ten rows
-- subquery returns the 10th value, using the offset parameter to the limit clause
-- parent query uses value to retrieve all rows equal too or above that value
SELECT supID, SUM(itmQty) AS quantity 
FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR) 
GROUP BY supID 
HAVING quantity >= (
	SELECT SUM(itmQty) AS quantity 
    FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
	WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)
    GROUP BY supID 
	ORDER BY quantity  DESC LIMIT 9,1
	)
ORDER BY quantity DESC;

---------------------------------------------------------------------------------------------------------------------------------------------

-- MIS dashboard
-- create a summary of supplements that have not sold during the last month
-- the EXCEPT and INTERSECT operators will be supported in version 10.3 of mariaDB 
SELECT supID
FROM SUPPLEMENT
WHERE NOT EXISTS (
	SELECT supID
	FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
	WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH) 
	)
ORDER BY supID ASC;

-- Detailed view
-- create a summary of any supplements that have not sold during previous financial years
SELECT supID
FROM SUPPLEMENT
WHERE NOT EXISTS (
	SELECT supID
	FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
	WHERE invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE
	)
ORDER BY supID ASC;

SELECT supID
FROM SUPPLEMENT
WHERE NOT EXISTS (
	SELECT supID
	FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
	WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29') 
	)
ORDER BY supID ASC;
	
SELECT supID
FROM SUPPLEMENT
WHERE NOT EXISTS (
	SELECT supID
	FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
	WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29')			
	)
ORDER BY supID ASC;
	
SELECT supID
FROM SUPPLEMENT
WHERE NOT EXISTS (
	SELECT supID
	FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
	WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29')	
	)
ORDER BY supID ASC;
	
SELECT supID
FROM SUPPLEMENT
WHERE NOT EXISTS (
	SELECT supID
	FROM INVOICE_ITEM JOIN INVOICE USING(invID) 
	WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29')	
	)
ORDER BY supID ASC;
		
---------------------------------------------------------------------------------------------------------------------------------------------

-- MIS dashboard
-- number of orders waiting for payment approval
SELECT COUNT(invID) AS "Orders Pending Approval"
FROM sale
WHERE saleStatus = 'PENDING'; 

-- Detailed view
-- summary of invoices that needs approval
-- sort by the earliest date
SELECT s.invID, i.invDate, i.invTotalCost
FROM sale s JOIN invoice i USING (invID)
WHERE saleStatus = 'PENDING'
ORDER BY i.invDate;

-- MIS dashboard
-- number of orders in the last month 
SELECT COUNT(invID)
FROM INVOICE
WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH);

-- Detailed view
-- number of orders during previous financial years
SELECT COUNT(invID)
FROM INVOICE 
WHERE invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE;

SELECT COUNT(invID)
FROM INVOICE 
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29');

SELECT COUNT(invID)
FROM INVOICE 
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29');

SELECT COUNT(invID)
FROM INVOICE 
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29');

SELECT COUNT(invID)
FROM INVOICE 
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29');
---------------------------------------------------------------------------------------------------------------------------------------------

-- MIS dashboard
-- number of orders cancelled in the last month 
-- status of sale is stored using the following four words - ('APPROVED', 'CANCELED', 'HISTORIC', 'PENDING')
SELECT COUNT(saleID) AS Cancel
FROM sale
WHERE saleStatus = 'CANCELED'
AND salePaymentDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH);

-- Detailed view
-- number of orders cancelled during previous financial years
SELECT COUNT(saleID) AS Cancel
FROM sale
WHERE saleStatus = 'CANCELED'
AND salePaymentDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE;

SELECT COUNT(saleID) AS Cancel
FROM sale
WHERE saleStatus = 'CANCELED'
AND salePaymentDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29');

SELECT COUNT(saleID) AS Cancel
FROM sale
WHERE saleStatus = 'CANCELED'
AND salePaymentDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29');


SELECT COUNT(saleID) AS Cancel
FROM sale
WHERE saleStatus = 'CANCELED'
AND salePaymentDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29');

SELECT COUNT(saleID) AS Cancel
FROM sale
WHERE saleStatus = 'CANCELED'
AND salePaymentDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29');

---------------------------------------------------------------------------------------------------------------------------------------------

-- MIS dashboard
-- create a summary of supplements returned in last month
SELECT supID, COUNT(supID) AS returned
FROM INVOICE_ITEM II JOIN RETURN_ITEM RI USING(itmID)
WHERE rtnItemDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
GROUP BY supID
ORDER BY returned DESC;

-- Detailed view
-- create a summary of supplements returned in previous financial years
SELECT supID, COUNT(supID) AS returned
FROM INVOICE_ITEM II JOIN RETURN_ITEM RI USING(itmID)
WHERE rtnItemDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE
GROUP BY supID
ORDER BY returned DESC;

SELECT supID, COUNT(supID) AS returned
FROM INVOICE_ITEM II JOIN RETURN_ITEM RI USING(itmID)
WHERE rtnItemDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29')
GROUP BY supID
ORDER BY returned DESC;

SELECT supID, COUNT(supID) AS returned
FROM INVOICE_ITEM II JOIN RETURN_ITEM RI USING(itmID)
WHERE rtnItemDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29')
GROUP BY supID
ORDER BY returned DESC;

SELECT supID, COUNT(supID) AS returned
FROM INVOICE_ITEM II JOIN RETURN_ITEM RI USING(itmID)
WHERE rtnItemDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29')
GROUP BY supID
ORDER BY returned DESC;

SELECT supID, COUNT(supID) AS returned
FROM INVOICE_ITEM II JOIN RETURN_ITEM RI USING(itmID)
WHERE rtnItemDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29')
GROUP BY supID
ORDER BY returned DESC;

---------------------------------------------------------------------------------------------------------------------------------------------

-- MIS dashboard
-- create a summary of the supplements sold in the last month, categorized by supplier
SELECT supplierID, COUNT(itmQty) AS quantity 
FROM SUPPLEMENT JOIN INVOICE_ITEM USING(supID) 
	JOIN INVOICE USING (invID)
WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH) 
GROUP BY supplierID 
ORDER BY quantity DESC;
-- Detailed view
-- create a summary of the supplements sold in previous financial years, categorized by supplier
SELECT supplierID, COUNT(itmQty) AS quantity 
FROM SUPPLEMENT JOIN INVOICE_ITEM USING(supID) 
	JOIN INVOICE USING (invID)
WHERE invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE
GROUP BY supplierID 
ORDER BY quantity DESC;

SELECT supplierID, COUNT(itmQty) AS quantity 
FROM SUPPLEMENT JOIN INVOICE_ITEM USING(supID) 
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29')
GROUP BY supplierID 
ORDER BY quantity DESC;

SELECT supplierID, COUNT(itmQty) AS quantity 
FROM SUPPLEMENT JOIN INVOICE_ITEM USING(supID) 
	JOIN INVOICE USING (invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29')
GROUP BY supplierID 
ORDER BY quantity DESC;

SELECT supplierID, COUNT(itmQty) AS quantity 
FROM SUPPLEMENT JOIN INVOICE_ITEM USING(supID) 
	JOIN INVOICE USING (invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29')
GROUP BY supplierID 
ORDER BY quantity DESC;

SELECT supplierID, COUNT(itmQty) AS quantity 
FROM SUPPLEMENT JOIN INVOICE_ITEM USING(supID) 
	JOIN INVOICE USING (invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29')
GROUP BY supplierID 
ORDER BY quantity DESC;

---------------------------------------------------------------------------------------------------------------------------------------------
-- MIS dashboard
-- create a regional breakdown of where the products are being delivered / sold
-- Northern region post codes between 0001 and 2899
-- invoice order used to count number of orders - in future expansion of system, a customer may have more than one order
-- Northern region
SELECT COUNT(invID) AS quantity
FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
WHERE CONVERT(cusPostCode, INTEGER) BETWEEN 0001 AND 2899;
-- Eastern region
SELECT COUNT(invID) AS quantity
FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
WHERE CONVERT(cusPostCode, INTEGER) BETWEEN 2900 AND 4730;
-- Southern region
SELECT COUNT(invID) AS quantity
FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
WHERE CONVERT(cusPostCode, INTEGER) BETWEEN 4731 AND 6499;
-- Western region
SELECT COUNT(invID) AS quantity
FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
WHERE CONVERT(cusPostCode, INTEGER) BETWEEN 6500 AND 8299;
-- Central region
SELECT COUNT(invID) AS quantity
FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
WHERE CONVERT(cusPostCode, INTEGER) BETWEEN 8300 AND 9999;
-- No region given
SELECT COUNT(invID) AS quantity
FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
WHERE CONVERT(cusPostCode, INTEGER) = 0000;

---------------------------------------------------------------------------------------------------------------------------------------------
-- MIS dashboard
-- Create a summary of how customers are being referred
SELECT cr.refName, COUNT(c.refID) AS quantity
FROM CUS_REFERENCE cr JOIN CUSTOMER c USING(refID)
GROUP BY cr.refName
ORDER BY quantity DESC;

-- End of commands relating to sales information


-- commands relating to financial data
-- data only included if sale has been approved or is recorded as historic
---------------------------------------------------------------------------------------------------------------------------------------------
-- MIS dashboard
-- total value of sales for current month
SELECT FORMAT(SUM(salePaymentAmt),2) AS val
FROM SALE JOIN INVOICE USING(invID)
WHERE saleStatus IN('APPROVED', 'HISTORIC') AND	
	invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH); 
-- Detailed view
-- total value of sales for the previous financial years

SELECT FORMAT(SUM(salePaymentAmt),2) AS val
FROM SALE JOIN INVOICE USING(invID)
WHERE saleStatus IN('APPROVED', 'HISTORIC') AND	
	invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE;
	
SELECT FORMAT(SUM(salePaymentAmt),2) AS val
FROM SALE JOIN INVOICE USING(invID)
WHERE saleStatus IN('APPROVED', 'HISTORIC') AND	
	invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29');
	
SELECT FORMAT(SUM(salePaymentAmt),2) AS val
FROM SALE JOIN INVOICE USING(invID)
WHERE saleStatus IN('APPROVED', 'HISTORIC') AND	
	invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29');
	
SELECT FORMAT(SUM(salePaymentAmt),2) AS val
FROM SALE JOIN INVOICE USING(invID)
WHERE saleStatus IN('APPROVED', 'HISTORIC') AND	
	invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29');
	
SELECT FORMAT(SUM(salePaymentAmt),2) AS val
FROM SALE JOIN INVOICE USING(invID)
WHERE saleStatus IN('APPROVED', 'HISTORIC') AND	
	invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29');
	
---------------------------------------------------------------------------------------------------------------------------------------------
-- MIS dashboard
-- total profit for the month
SELECT FORMAT(SUM(supPercInc), 2) AS profit
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH);
-- Detailed view
-- total profit for previous financial years
SELECT FORMAT(SUM(supPercInc), 2) AS profit
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE;
	
SELECT FORMAT(SUM(supPercInc), 2) AS profit
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29');
	
SELECT FORMAT(SUM(supPercInc), 2) AS profit
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29');
	
SELECT FORMAT(SUM(supPercInc), 2) AS profit
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29');
	
SELECT FORMAT(SUM(supPercInc), 2) AS profit
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29');
---------------------------------------------------------------------------------------------------------------------------------------------
-- MIS dashboard
-- total tax for the month
SELECT FORMAT(SUM(supCostInc - supCostExc ), 2) AS tax
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH);
-- Detailed view
-- -- total tax for previous financial years
SELECT FORMAT(SUM(supCostInc - supCostExc ), 2) AS tax
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE;
	
SELECT FORMAT(SUM(supCostInc - supCostExc ), 2) AS tax
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29');
	
SELECT FORMAT(SUM(supCostInc - supCostExc ), 2) AS tax
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29');
	
SELECT FORMAT(SUM(supCostInc - supCostExc ), 2) AS tax
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29');
	
SELECT FORMAT(SUM(supCostInc - supCostExc ), 2) AS tax
FROM SALE JOIN INVOICE USING(invID) 
	JOIN INVOICE_ITEM USING(invID)
	JOIN SUPPLEMENT_COST USING(supID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29');
 

---------------------------------------------------------------------------------------------------------------------------------------------
-- MIS dashboard
-- calculate the average price paid for an order this month
SELECT FORMAT(AVG(invTotalCost), 2) AS avgPrice
FROM SALE JOIN INVOICE USING(invID) 
WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH);
-- Detailed view
-- calculate the average price paid for an order for previous financial years
SELECT FORMAT(AVG(invTotalCost), 2) AS avgPrice
FROM SALE JOIN INVOICE USING(invID) 
WHERE invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE;

SELECT FORMAT(AVG(invTotalCost), 2) AS avgPrice
FROM SALE JOIN INVOICE USING(invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-03-01') AND CONCAT(YEAR(CURRENT_DATE),'-02-29');

SELECT FORMAT(AVG(invTotalCost), 2) AS avgPrice
FROM SALE JOIN INVOICE USING(invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)),'-02-29');

SELECT FORMAT(AVG(invTotalCost), 2) AS avgPrice
FROM SALE JOIN INVOICE USING(invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR)),'-02-29');

SELECT FORMAT(AVG(invTotalCost), 2) AS avgPrice
FROM SALE JOIN INVOICE USING(invID)
WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 4 YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 3 YEAR)),'-02-29');
---------------------------------------------------------------------------------------------------------------------------------------------
-- End of commands relating to financial information

-- Start of commands relating to pricing information

-- MIS dashboard
-- show the products with the lowest profit markup
SELECT  supCostDate, supCostExc, supCostInc, supPercInc, supID
FROM supplement_cost
WHERE supPercInc = (
    SELECT MIN(supPercInc)
    FROM supplement_cost);
-- show the products with the highest profit markup
SELECT  supCostDate, supCostExc, supCostInc, supPercInc, supID
FROM supplement_cost
WHERE supPercInc = (
    SELECT MAX(supPercInc)
    FROM supplement_cost);

-- Detailed view
-- show the products with the lowest profit markup and the qty sold since the start of records
SELECT supID, supPercInc AS "Lowest Mark Up", COUNT(itmQty) AS "Quantity Sold"
FROM supplement_cost JOIN INVOICE_ITEM USING(supID)
WHERE supPercInc = (
    SELECT MIN(supPercInc)
    FROM supplement_cost)
GROUP BY supID;

-- show the products with the highest profit markup and the qty sold since the start of records
SELECT supID, supPercInc AS "Highest Mark Up", COUNT(itmQty) AS "Quantity Sold"
FROM supplement_cost JOIN INVOICE_ITEM USING(supID)
WHERE supPercInc = (
    SELECT MAX(supPercInc)
    FROM supplement_cost)
GROUP BY supID;

-- Calcualte the average profit of products sold by supplier and the standard deviation of the profit for each supplier
SELECT s.supplierID, COUNT(*) AS "Number of Products", AVG(sc.supPercInc) AS "Average Profit", STDDEV(sc.supPercInc) AS "Standard Deviation"
FROM supplement s JOIN supplement_cost sc USING(supID)
GROUP BY supplierID;

---------------------------------------------------------------------------------------------------------------------------------------------
