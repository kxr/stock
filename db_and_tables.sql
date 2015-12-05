create datbase ktdb;
grant all privileges on ktdb.* to 'ktdbuser'@'localhost' identified by 'KtDbP@55KtDbP@55';
use ktd;
create table stock_sale ( 
	sale_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date DATE,
	item_id INT UNSIGNED NOT NULL,
	sale_type VARCHAR(20),
	invoice_no VARCHAR(20),
	uprice DECIMAL(10,3) UNSIGNED,
	qty DECIMAL(10,3) UNSIGNED,
	comments VARCHAR(50)
	);
create table stock_hold ( 
	hold_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date DATE,
	item_id INT UNSIGNED NOT NULL,
	hold_type VARCHAR(20),
	qty DECIMAL(10,3) UNSIGNED,
	comments VARCHAR(50),
	status tinyint unsigned default 0
	);
create table stock_purchase ( 
	pur_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date DATE,
	item_id INT UNSIGNED NOT NULL,
	pur_type VARCHAR(20),
	invoice_no VARCHAR(20),
	uprice DECIMAL(10,3) UNSIGNED,
	qty DECIMAL(10,3) UNSIGNED,
	comments VARCHAR(50)
	);
create table stock_vendors ( 
	ven_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ven_name VARCHAR(30),
	ven_phone VARCHAR(30),
	ven_mobile VARCHAR(30),
	ven_fax VARCHAR(30),
	ven_email VARCHAR(30),
	ven_detail VARCHAR(255)
	);
create table stock_items ( 
	item_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ven_id INT UNSIGNED,
	item_name VARCHAR(50)
	);
create table stock_config (
	name VARCHAR(50) NOT NULL,
	value VARCHAR(50)
	);
insert into stock_config values ( "sale_type", "Cash");
insert into stock_config values ( "sale_type", "Credit");
insert into stock_config values ( "purchase_type", "Cash");
insert into stock_config values ( "purchase_type", "Cheque");
insert into stock_config values ( "hold_type", "Main Branch");
insert into stock_config values ( "hold_type", "Work Site");
insert into stock_config values ( "currency", "Dhs");
insert into stock_config values ( "company_name", "Khurshid Traders");

