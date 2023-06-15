#CREATE DATABASE secondhandstore;
USE secondhandstore;
#DROP TABLES IF EXISTS sellers;
#DROP TABLES IF EXISTS items;

CREATE TABLE `sellers` (
    `ID` int  auto_increment ,
    `Name` varchar(100)  NOT NULL ,
    `PhoneNumber` varchar(11)  NOT NULL ,
    `Address` varchar(50)  NOT NULL , 
        PRIMARY KEY (
        `ID`
    )
);

CREATE TABLE `items` (
    `ID` int  auto_increment ,
    `Name` varchar(100)  NOT NULL ,
    `TypeOfItem` varchar(20)  NOT NULL ,
    `Size` varchar(10)  NOT NULL ,
    `Color` varchar(20)  NOT NULL ,
    `Price` int  NOT NULL ,
    `SellerID` int  NOT NULL ,
    `Sold` boolean  NOT NULL ,
    PRIMARY KEY (
        `ID`
    )
);

ALTER TABLE `items` ADD CONSTRAINT `fk_items_SellerID` FOREIGN KEY(`SellerID`)
REFERENCES `sellers` (`ID`);



