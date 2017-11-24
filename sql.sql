DROP TABLE IF EXISTS `ratings`;
DROP TABLE IF EXISTS `orderSpec`;
DROP TABLE IF EXISTS `order`;
DROP TABLE IF EXISTS `basketItem`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `prodCat`;
DROP TABLE IF EXISTS `user`;


CREATE TABLE `user`(
       userID INT(11) NOT NULL auto_increment,
       fName varchar(32) NOT NULL,
       lName varchar(64) NOT NULL,
       address varchar(64) NOT NULL,
       userType ENUM('Admin', 'Customer') DEFAULT 'Customer' NOT NULL,
       password varchar(64) NOT NULL,
       email varchar(128) NOT NULL,
       PRIMARY KEY(userID)
);

CREATE TABLE `prodCat`(
       title varchar(32) NOT NULL,
       PRIMARY KEY(title)
);

CREATE TABLE `product`(
       productID INT(11) NOT NULL auto_increment,
       title varchar(32) NOT NULL,
       `desc` TEXT NOT NULL,
       qty int(11) NOT NULL,
       price int(11) NOT NULL,
       prodCatTitle varchar(32) NOT NULL,
       visible BOOLEAN DEFAULT TRUE NOT NULL,
       PRIMARY KEY(productID),
       FOREIGN KEY(prodCatTitle) REFERENCES prodCat(title)
);

CREATE TABLE `order`(
       orderID int(11) NOT NULL auto_increment,
       userID int(11) NOT NULL,
       orderDate datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
       totalPrice int(11) NOT NULL,
       orderStatus ENUM('Accepted', 'Declined', 'Processing') DEFAULT 'Processing' NOT NULL,
       PRIMARY KEY(orderID),
       FOREIGN KEY(userID) REFERENCES `user`(userID)
);


CREATE TABLE `orderSpec`(
       orderID int(11) NOT NULL,
       productID int(11) NOT NULL,
       qty int(11) NOT NULL,
       PRIMARY KEY(orderID, productID),
       FOREIGN KEY(orderID) REFERENCES `order`(orderID),
       FOREIGN KEY(productID) REFERENCES product(productID)
);

CREATE TABLE `ratings`(
       userID int(11) NOT NULL,
       productID int(11) NOT NULL,
       rating int(1),
       comment text,
       CHECK ((rating >= 1 AND rating <= 5) OR rating =NULL),
       PRIMARY KEY(userID, productID),
       FOREIGN KEY(userID) REFERENCES `user`(userID),
       FOREIGN KEY(productID) REFERENCES product(productID)
);

CREATE TABLE `basketItem`(
       userID int(11) NOT NULL,
       productID int(11) NOT NULL,
       qty int(11) NOT NULL,
       PRIMARY KEY(userID, productID),
       FOREIGN KEY(userID) REFERENCES `user`(userID),
       FOREIGN KEY(productID) REFERENCES product(productID)
);
