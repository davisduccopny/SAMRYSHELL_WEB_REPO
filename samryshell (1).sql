-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 14, 2024 lúc 10:28 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `samryshell`
--

DELIMITER $$
--
-- Thủ tục
--
CREATE  PROCEDURE `createSaleAndSaleDetail_cart` (IN `email` VARCHAR(255), IN `status` VARCHAR(255), IN `payment` VARCHAR(255))   BEGIN
    DECLARE product_id_val INT(11);
    DECLARE quantity_val INT(11);
    DECLARE product_price DECIMAL(10,2); -- Khai báo biến để lưu giá sản phẩm
    
    -- Tạo bản ghi trong bảng sale
    INSERT INTO sale (email, status, payment) VALUES (email, status, payment);
    
    -- Lấy sale_id mới được tạo
    SET @sale_id = LAST_INSERT_ID();
    
    -- Lấy tất cả các dòng từ bảng cart với email nhất định và lưu vào bảng tạm thời
    CREATE TEMPORARY TABLE cart_temp AS SELECT product_id, quantity FROM cart WHERE email = email;
    
    -- Lặp qua các dòng kết quả từ bảng tạm thời và thêm chúng vào bảng sale_detail
    WHILE (SELECT COUNT(*) FROM cart_temp) > 0 DO
        -- Lấy dòng đầu tiên từ bảng tạm thời
        SELECT product_id, quantity INTO product_id_val, quantity_val FROM cart_temp LIMIT 1;
        
        -- Lấy giá của sản phẩm từ bảng product
        SELECT price INTO product_price FROM product WHERE id = product_id_val;
        
        -- Thêm dòng kết quả vào bảng sale_detail và cập nhật giá vào trường total
        INSERT INTO sale_detail (sale_id, product_id, quantity, total, price) VALUES (@sale_id, product_id_val, quantity_val, quantity_val * product_price,product_price );
        
        -- Xóa dòng đã thêm từ bảng tạm thời
        DELETE FROM cart_temp LIMIT 1;
    END WHILE;
    
    -- Xóa bảng tạm thời
    DROP TABLE cart_temp;
    
    -- Tính toán tổng giá trị của các dòng trong bảng sale_detail
    SELECT SUM(total) INTO @grand_total FROM sale_detail WHERE sale_id = @sale_id;
    
    -- Cập nhật giá trị tổng vào trường grand_total của bảng sale
    UPDATE sale 
    SET grand_total = @grand_total, total=@grand_total
    WHERE sale_id = @sale_id;
    
    -- Xóa dữ liệu từ bảng cart
    DELETE FROM cart WHERE email = email;
END$$

CREATE  PROCEDURE `create_quotation` (IN `email` VARCHAR(255), IN `status` VARCHAR(50), IN `created_at` DATETIME, IN `tax` DECIMAL(10,2), IN `discount` DECIMAL(10,2), IN `description` TEXT, IN `items` JSON)   BEGIN
    DECLARE total DECIMAL(10, 2);
    DECLARE orderrand DECIMAL(10, 2);
    DECLARE grandtotal DECIMAL(10, 2);
    DECLARE alltotalproduct DECIMAL(10, 2);
    DECLARE success_flag BOOLEAN DEFAULT TRUE;
    DECLARE error_message VARCHAR(255) DEFAULT '';
    DECLARE grandtotalall DECIMAL(10, 2);
    -- Insert into quotation table
    INSERT INTO quotation(email, status, created_at, tax, discount, description)
    VALUES (email, status, created_at, IFNULL(tax, 0), IFNULL(discount, 0), description);

    IF ROW_COUNT() = 0 THEN
        SET success_flag = FALSE;
        SET error_message = 'Failed to insert into quotation table';
    ELSE
        SET success_flag = TRUE;
    END IF;

    -- Get the newly inserted sale_id
    SET @quotation_id = LAST_INSERT_ID();
    -- Insert into sale_detail table
    SET @items_count = JSON_LENGTH(items);
    SET @alltotalproduct = 0;
    WHILE @items_count > 0 DO
    BEGIN
        SET @item = JSON_UNQUOTE(JSON_EXTRACT(items, CONCAT('$[', @items_count - 1, ']')));
        SET @product_id = JSON_UNQUOTE(JSON_EXTRACT(@item, '$.product_id'));
        SET @quantity = JSON_UNQUOTE(JSON_EXTRACT(@item, '$.quantity'));

        -- Get product details
        SELECT product.price, product.minium_quantity, product.discount, product.tax
        INTO @price, @minium_quantity, @discount, @product_tax
        FROM product
        WHERE product.id = @product_id;

        -- Calculate total for sale_detail
        IF @quantity >= @minium_quantity THEN
            SET @orderrand = @price * @quantity * @discount;
        ELSE
            SET @orderrand = 0;
        END IF;

        SET @total = (@price * @quantity) + IFNULL((@product_tax * @quantity * @price), 0) - @orderrand;
        SET @alltotalproduct = @alltotalproduct + @total;
        -- Insert into sale_detail
        INSERT INTO quotation_detail(quotation_id, product_id, quantity, total,price,discount,tax,minium_quantity)
        VALUES (@quotation_id, @product_id, @quantity, @total,@price,@discount,@product_tax,@minium_quantity);

        SET @items_count = @items_count - 1;
        END;
    END WHILE;

        UPDATE quotation
        SET total = @alltotalproduct
        WHERE quotation_id = @quotation_id;

    SET @grandtotalall = @alltotalproduct + @alltotalproduct * IFNULL(tax, 0) - @alltotalproduct * IFNULL(discount, 0);
    UPDATE quotation
    SET quotation.grand_total = @grandtotalall
    WHERE quotation.quotation_id = @quotation_id;



    -- Set the final result
    IF success_flag THEN
        SET error_message = 'Success';
    END IF;

    SELECT success_flag AS success, error_message;
END$$

CREATE  PROCEDURE `create_sale` (IN `email` VARCHAR(255), IN `status` VARCHAR(50), IN `payment_type` VARCHAR(50), IN `created_at` DATETIME, IN `ship` DECIMAL(10,2), IN `tax` DECIMAL(10,2), IN `discount_id` BIGINT, IN `description` TEXT, IN `items` JSON)   BEGIN
    DECLARE total DECIMAL(10, 2);
    DECLARE orderrand DECIMAL(10, 2);
    DECLARE grandtotal DECIMAL(10, 2);
    DECLARE alltotalproduct DECIMAL(10, 2);
    DECLARE success_flag BOOLEAN DEFAULT TRUE;
    DECLARE error_message VARCHAR(255) DEFAULT '';
    DECLARE grandtotalall DECIMAL(10, 2);
    -- Insert into sale table
    INSERT INTO sale(email, status, created_at, ship, tax, discount_id, description)
    VALUES (email, status, created_at, IFNULL(ship, 0), IFNULL(tax, 0), discount_id, description);

    IF ROW_COUNT() = 0 THEN
        SET success_flag = FALSE;
        SET error_message = 'Failed to insert into sale table';
    ELSE
        SET success_flag = TRUE;
    END IF;

    -- Get the newly inserted sale_id
    SET @sale_id = LAST_INSERT_ID();
    -- Insert into sale_detail table
    SET @items_count = JSON_LENGTH(items);
    SET @alltotalproduct = 0;
    WHILE @items_count > 0 DO
    BEGIN
        
        SET @item = JSON_UNQUOTE(JSON_EXTRACT(items, CONCAT('$[', @items_count - 1, ']')));
        SET @product_id = JSON_UNQUOTE(JSON_EXTRACT(@item, '$.product_id'));
        SET @quantity = JSON_UNQUOTE(JSON_EXTRACT(@item, '$.quantity'));

        -- Get product details
        SELECT product.price, product.minium_quantity, product.discount, product.tax
        INTO @price, @minium_quantity, @discount, @product_tax
        FROM product
        WHERE product.id = @product_id;

        -- Calculate total for sale_detail
        IF @quantity >= @minium_quantity THEN
            SET @orderrand = @price * @quantity * @discount;
        ELSE
            SET @orderrand = 0;
        END IF;

        SET @total = (@price * @quantity) + IFNULL((@product_tax * @quantity * @price), 0) - @orderrand;
        SET @alltotalproduct = @alltotalproduct + @total;
        -- Insert into sale_detail
        INSERT INTO sale_detail(sale_id, product_id, quantity, total,price,discount,tax,minium_quantity)
        VALUES (@sale_id, @product_id, @quantity, @total,@price,@discount,@product_tax,@minium_quantity);
        
        UPDATE product
        SET quantity = quantity - @quantity
        WHERE id = @product_id;

        SET @items_count = @items_count - 1;
        END;
    END WHILE;

        UPDATE sale
        SET total = @alltotalproduct
        WHERE sale_id = @sale_id;

    -- Update other sale fields based on discount and status
        SELECT discount.discount_amount, discount.status, discount.minium_value, discount.max_discount
        INTO @discount_amount, @discount_status, @minium_value, @max_discount
        FROM discount
        WHERE discount.discount_id = discount_id
        LIMIT 1;

    
    IF @discount_status = 1 AND @alltotalproduct >= @minium_value THEN
        SET @orderrand = @alltotalproduct * @discount_amount;
        IF @orderrand >= @max_discount THEN
            SET @orderrand = @max_discount;
        END IF;
        UPDATE discount 
        SET discount.quantity = discount.quantity -1 
        WHERE discount.discount_id = discount_id;

      
    ELSE
    
        SET @orderrand = 0;
         UPDATE sale
         SET sale.discount_id = 0
         WHERE sale.sale_id = @sale_id;
    END IF;

    SET @grandtotalall = @alltotalproduct + @alltotalproduct * IFNULL(tax, 0) + IFNULL(ship, 0) -  @orderrand;
    UPDATE sale
    SET sale.grand_total = @grandtotalall
    WHERE sale.sale_id = @sale_id;

    IF status = 'Complete' THEN
        UPDATE sale
        SET sale.paid = @grandtotalall, sale.payment = 'Paid', sale.due = 0
        WHERE sale.sale_id = @sale_id;
    ELSE
        UPDATE sale
        SET sale.paid = 0, sale.payment = 'Due', sale.due = @grandtotalall
        WHERE sale.sale_id = @sale_id;
    END IF;

    -- update cart 
SELECT COUNT(*) INTO @cart_count FROM cart WHERE cart.email = email;
IF FOUND_ROWS() > 0 THEN
    DELETE FROM cart WHERE cart.email = email;
END IF;



    -- Set the final result
    IF success_flag THEN
        SET error_message = 'Success';
    END IF;

    SELECT success_flag AS success, error_message;
END$$

CREATE  PROCEDURE `create_salereturn` (IN `reference` VARCHAR(255), IN `status` VARCHAR(50), IN `paymentstatus` VARCHAR(50), IN `paymentname` VARCHAR(50), IN `reason` TEXT, IN `product_id` BIGINT, IN `returndate` DATETIME)   BEGIN
    DECLARE orderrand DECIMAL(10, 2);
    DECLARE alltotalproduct DECIMAL(10, 2);
    DECLARE success_flag BOOLEAN DEFAULT TRUE;
    DECLARE error_message VARCHAR(255) DEFAULT '';

    DECLARE return_id BIGINT;
    DECLARE statuspayment VARCHAR(50);
    DECLARE paid_return DECIMAL(10, 2);
    DECLARE due_return DECIMAL(10, 2);

    -- SEARCH FOR SALE ID
    SELECT sale.sale_id, IFNULL(sale.discount_id,0), IFNULL(sale.tax,0), IFNULL(sale.ship,0), sale.email  
    INTO @sale_id, @discount_id, @tax_sale, @ship_sale, @email_sale 
    FROM sale WHERE sale.reference = reference LIMIT 1;

    SELECT sale_detail.total, sale_detail.quantity 
    INTO @totalproduct, @quantitydetail 
    FROM sale_detail WHERE sale_detail.sale_id = @sale_id AND sale_detail.product_id = product_id LIMIT 1;


    IF @discount_id != 0 THEN
        SELECT discount.discount_amount INTO @discount_amount 
        FROM discount WHERE discount.discount_id = @discount_id LIMIT 1;
        SET @orderrand = @totalproduct * @discount_amount;
    ELSE
        SET @orderrand = 0;
    END IF;

    IF status = 'Complete' THEN
        SET @paid_return = @totalproduct + @totalproduct * @tax_sale - @orderrand;
        SET @due_return = 0;
    ELSE
        SET @due_return = @totalproduct + @totalproduct * @tax_sale - @orderrand;
        SET @paid_return = 0;
    END IF;

    SET @alltotalproduct = @totalproduct + @totalproduct * @tax_sale - @orderrand;

    -- INSERT INTO RETURN TABLE
    INSERT INTO sale_return (sale_id, email, product_id, quantity, status, payment, total, tax, discount, grand_total, paid, due, reason, created_at) 
    VALUES (@sale_id, @email_sale, product_id, @quantitydetail, status,paymentstatus , 
    @totalproduct, @tax_sale, @orderrand, @alltotalproduct, @paid_return, @due_return, reason, returndate);

    IF ROW_COUNT() = 0 THEN
        SET success_flag = FALSE;
        SET error_message = 'Failed to insert into sale table';
    ELSE
        SET success_flag = TRUE;
    END IF;

    -- UPDATE PRODUCT QUANTITY
    UPDATE product SET product.quantity = product.quantity + @quantitydetail WHERE product.id = product_id;

    -- GET RETURN ID
    SET @return_id = LAST_INSERT_ID();

    -- CHECK PAYMENT STATUS
    IF paymentstatus = 'Paid' THEN
        SET @statuspayment = 'Complete';
        INSERT INTO payment_return_detail (return_id, payment_name, valueplus, status)
        VALUES (@return_id, paymentname, @paid_return, @statuspayment);
    END IF;
    
    -- SET RESULT
    IF success_flag THEN
        SET error_message = 'Success';
    END IF;

    SELECT success_flag AS success, error_message;
END$$

CREATE  PROCEDURE `update_quotation` (IN `quotation_id` BIGINT, IN `email` VARCHAR(255), IN `status` VARCHAR(50), IN `created_at` DATETIME, IN `tax` DECIMAL(10,2), IN `discount` DECIMAL(10,2), IN `description` TEXT, IN `items` JSON)   BEGIN
    DECLARE total DECIMAL(10, 2);
    DECLARE orderrand DECIMAL(10, 2);
    DECLARE grandtotal DECIMAL(10, 2);
    DECLARE alltotalproduct DECIMAL(10, 2);
    DECLARE success_flag BOOLEAN DEFAULT TRUE;
    DECLARE error_message VARCHAR(255) DEFAULT '';
    DECLARE grandtotalall DECIMAL(10, 2);


    DELETE FROM quotation_detail WHERE quotation_detail.quotation_id = quotation_id;
    -- updateto quotation table
    UPDATE quotation
    SET quotation.email = email,
    quotation.status = status,
    quotation.created_at = created_at,
    quotation.tax = IFNULL(tax, 0),
    quotation.discount = IFNULL(discount, 0),
    quotation.description = description
    WHERE quotation.quotation_id = quotation_id;

    IF ROW_COUNT() = 0 THEN
        SET success_flag = FALSE;
        SET error_message = 'Failed to insert into quotation table';
    ELSE
        SET success_flag = TRUE;
    END IF;

    -- Insert into sale_detail table
    SET @items_count = JSON_LENGTH(items);
    SET @alltotalproduct = 0;
    WHILE @items_count > 0 DO
    BEGIN
        SET @item = JSON_UNQUOTE(JSON_EXTRACT(items, CONCAT('$[', @items_count - 1, ']')));
        SET @product_id = JSON_UNQUOTE(JSON_EXTRACT(@item, '$.product_id'));
        SET @quantity = JSON_UNQUOTE(JSON_EXTRACT(@item, '$.quantity'));

        -- Get product details
        SELECT product.price, product.minium_quantity, product.discount, product.tax
        INTO @price, @minium_quantity, @discount, @product_tax
        FROM product
        WHERE product.id = @product_id;

        -- Calculate total for sale_detail
        IF @quantity >= @minium_quantity THEN
            SET @orderrand = @price * @quantity * @discount;
        ELSE
            SET @orderrand = 0;
        END IF;

        SET @total = (@price * @quantity) + IFNULL((@product_tax * @quantity * @price), 0) - @orderrand;
        SET @alltotalproduct = @alltotalproduct + @total;
        -- Insert into sale_detail
        INSERT INTO quotation_detail(quotation_id, product_id, quantity, total,price,discount,tax,minium_quantity)
        VALUES (quotation_id, @product_id, @quantity, @total,@price,@discount,@product_tax,@minium_quantity);

        SET @items_count = @items_count - 1;
        END;
    END WHILE;

        UPDATE quotation
        SET quotation.total = @alltotalproduct
        WHERE quotation.quotation_id = quotation_id;

    SET @grandtotalall = @alltotalproduct + @alltotalproduct * IFNULL(tax, 0) - @alltotalproduct * IFNULL(discount, 0);
    UPDATE quotation
    SET quotation.grand_total = @grandtotalall
    WHERE quotation.quotation_id = quotation_id;



    -- Set the final result
    IF success_flag THEN
        SET error_message = 'Success';
    END IF;

    SELECT success_flag AS success, error_message;
END$$

CREATE  PROCEDURE `update_sale` (IN `email` VARCHAR(255), IN `status` VARCHAR(50), IN `ship` DECIMAL(10,2), IN `tax` DECIMAL(10,2), IN `discount_id` BIGINT, IN `description` TEXT, IN `items` JSON, IN `sale_id` BIGINT)   BEGIN
    DECLARE total DECIMAL(10, 2);
    DECLARE orderrand DECIMAL(10, 2);
    DECLARE orderrand2 DECIMAL(10, 2);
    DECLARE grandtotal DECIMAL(10, 2);
    DECLARE alltotalproduct DECIMAL(10, 2);
    DECLARE success_flag BOOLEAN DEFAULT TRUE;
    DECLARE error_message VARCHAR(255) DEFAULT '';
    DECLARE grandtotalall DECIMAL(10, 2);
    DECLARE discount_id_old BIGINT;
    DECLARE temp_product_id BIGINT;
    DECLARE temp_quantity DECIMAL(10, 2);



    -- SEARCH FOR SALE ID
    SELECT sale.discount_id INTO @discount_id_old FROM sale WHERE sale.sale_id = sale_id LIMIT 1;
    IF (@discount_id_old != 0) THEN
        UPDATE discount SET discount.quantity = discount.quantity + 1 WHERE discount.discount_id = @discount_id_old;
    END IF;
    

    -- ROLLBACK PRODUCT QUANTITY
  -- CREATE A TEMPORARY TABLE TO STORE PRODUCT ID AND QUANTITY
CREATE TEMPORARY TABLE temp_sale_products (
    product_id BIGINT,
    quantity DECIMAL(10, 2)
);

-- INSERT PRODUCT ID AND QUANTITY FROM SALE DETAILS INTO TEMPORARY TABLE
INSERT INTO temp_sale_products (product_id, quantity)
SELECT product_id, quantity
FROM sale_detail
WHERE sale_detail.sale_id =sale_id;

-- LOOP THROUGH EACH PRODUCT IN TEMPORARY TABLE AND UPDATE PRODUCT QUANTITY
WHILE (SELECT COUNT(*) FROM temp_sale_products) > 0 DO
    -- GET PRODUCT ID AND QUANTITY FOR THE FIRST ROW
    SELECT product_id, quantity
    INTO @temp_product_id, @temp_quantity
    FROM temp_sale_products
    LIMIT 1;

    -- UPDATE PRODUCT QUANTITY
    UPDATE product
    SET product.quantity = product.quantity+ @temp_quantity
    WHERE product.id = @temp_product_id;

    -- DELETE THE FIRST ROW FROM TEMPORARY TABLE
    DELETE FROM temp_sale_products
    WHERE product_id = @temp_product_id;
END WHILE;

-- DROP THE TEMPORARY TABLE
DROP TEMPORARY TABLE temp_sale_products;

    -- END ROLLBACK PRODUCT QUANTITY
    -- DELETE SALE DETAIL
    DELETE FROM sale_detail WHERE sale_detail.sale_id = sale_id;
    -- UPDATE SALE
    UPDATE sale
    SET
        email = email,
        status = status,
        ship = IFNULL(ship, 0),
        tax = IFNULL(tax, 0),
        discount_id = discount_id,
        description = description
    WHERE sale.sale_id = sale_id;

    IF ROW_COUNT() = 0 THEN
        SET success_flag = FALSE;
        SET error_message = 'Failed to update sale table';
    ELSE
        SET success_flag = TRUE;
    END IF;

    -- Get the newly updated sale_id
    SET @sale_id = sale_id;

    -- Insert into sale_detail table
    SET @items_count = JSON_LENGTH(items);
    SET @alltotalproduct = 0;

    WHILE @items_count > 0 DO
        SET @item = JSON_UNQUOTE(JSON_EXTRACT(items, CONCAT('$[', @items_count - 1, ']')));
        SET @product_id = JSON_UNQUOTE(JSON_EXTRACT(@item, '$.product_id'));
        SET @quantity = JSON_UNQUOTE(JSON_EXTRACT(@item, '$.quantity'));

        -- Get product details
        SELECT product.price, product.minium_quantity, product.discount, product.tax
        INTO @price, @minium_quantity, @discount, @product_tax
        FROM product
        WHERE product.id = @product_id;

        -- Calculate total for sale_detail
        IF @quantity >= @minium_quantity THEN
            SET @orderrand = @price * @quantity * @discount;
        ELSE
            SET @orderrand = 0;
        END IF;

        SET @total = (@price * @quantity) + IFNULL((@product_tax * @quantity * @price), 0) - @orderrand;
        SET @alltotalproduct = @alltotalproduct + @total;

        -- Insert into sale_detail
        INSERT INTO sale_detail(sale_id, product_id, quantity, total,price,discount,tax,minium_quantity)
        VALUES (@sale_id, @product_id, @quantity, @total,@price,@discount,@product_tax,@minium_quantity);

        -- Update product quantity
        UPDATE product
        SET product.quantity = product.quantity - @quantity
        WHERE product.id = @product_id;

        SET @items_count = @items_count - 1;
    END WHILE;

    -- Update sale total
    UPDATE sale
    SET total = @alltotalproduct
    WHERE sale.sale_id = @sale_id;

    -- Update other sale fields based on discount and status
    SELECT discount.discount_amount, discount.status, discount.minium_value, discount.max_discount
    INTO @discount_amount, @discount_status, @minium_value, @max_discount
    FROM discount
    WHERE discount.discount_id = discount_id
    LIMIT 1;

    IF @discount_status = 1 AND @alltotalproduct >= @minium_value  THEN
        SET @orderrand2 = @alltotalproduct * @discount_amount;
        
        IF @orderrand2 >= @max_discount THEN
            SET @orderrand2 = @max_discount;
        END IF;
        UPDATE discount
        SET discount.quantity = discount.quantity - 1
        WHERE discount.discount_id = discount_id;
    ELSE
        SET @orderrand2 = 0;
        UPDATE sale
        SET sale.discount_id = 0
        WHERE sale.sale_id = sale_id;
       
        
    END IF;

    SET @grandtotalall = @alltotalproduct + (@alltotalproduct * IFNULL(tax, 0)) + IFNULL(ship, 0) - @orderrand2;
    UPDATE sale
    SET grand_total = @grandtotalall
    WHERE sale.sale_id = @sale_id;
    -- Update sale grand_total, paid, and due
    IF status = 'Complete' THEN
        UPDATE sale
        SET paid = @grandtotalall, payment = 'Paid', due = 0
        WHERE sale.sale_id = @sale_id;
    ELSE
        UPDATE sale
        SET paid = 0, payment = 'Due', due = @grandtotalall
        WHERE sale.sale_id = @sale_id;
    END IF;


    -- Update cart
    DELETE FROM cart WHERE cart.email = email;

    -- Set the final result
    IF success_flag THEN
        SET error_message = 'Success';
    END IF;
	
     
END$$

CREATE  PROCEDURE `update_salereturn` (IN `status` VARCHAR(50), IN `paymentstatus` VARCHAR(50), IN `paymentname` VARCHAR(50), IN `reason` TEXT, IN `returndate` DATETIME, IN `return_id` BIGINT)   BEGIN
    DECLARE success_flag BOOLEAN DEFAULT TRUE;
    DECLARE error_message VARCHAR(255) DEFAULT '';

    -- SEARCH FOR SALE ID
    SELECT sale_return.grand_total, sale_return.paid INTO @grand_totalupdate, @paid_return FROM sale_return WHERE sale_return.id = return_id LIMIT 1;

    IF paymentstatus = 'Paid' AND @paid_return <= 0 AND status != 'Complete' THEN
        UPDATE sale_return
        SET sale_return.paid = @grand_totalupdate, sale_return.due = 0, sale_return.status = status, sale_return.reason = reason, sale_return.created_at = returndate, sale_return.payment = 'Paid'
        WHERE sale_return.id = return_id;
    ELSEIF status = 'Complete' AND paymentstatus != 'Paid' THEN
        UPDATE sale_return
        SET sale_return.paid = @grand_totalupdate, sale_return.due = 0, sale_return.status = status, sale_return.reason = reason, sale_return.created_at = returndate, sale_return.payment = 'Paid'
        WHERE sale_return.id = return_id;
    ELSEIF status = 'Complete' AND paymentstatus = 'Paid' THEN
        UPDATE sale_return
        SET sale_return.paid = @grand_totalupdate, sale_return.due = 0, sale_return.status = status, sale_return.reason = reason, sale_return.created_at = returndate, sale_return.payment = 'Paid'
        WHERE sale_return.id = return_id;
    ELSE
        UPDATE sale_return
        SET sale_return.status = status, sale_return.reason = reason, sale_return.created_at = returndate, sale_return.payment = paymentstatus
        WHERE sale_return.id = return_id;
    END IF;

    SELECT payment_return_detail.return_id INTO @return_id_payment FROM payment_return_detail WHERE payment_return_detail.return_id = return_id LIMIT 1;
    IF (@return_id_payment IS NULL OR ROW_COUNT()=0) AND status ='Complete' THEN
        INSERT INTO payment_return_detail (return_id, payment_name, valueplus, status)
        VALUES (return_id, paymentname, @grand_totalupdate, status);
    ELSE
        UPDATE payment_return_detail
        SET payment_return_detail.payment_name = paymentname
        WHERE payment_return_detail.return_id = return_id;
    END IF;

    IF ROW_COUNT() = 0 THEN
        SET success_flag = FALSE;
        SET error_message = 'Failed to update sale_return table';
    ELSE
        SET success_flag = TRUE;
    END IF;

    IF success_flag THEN
        SET error_message = 'Success';
    END IF;

    SELECT success_flag AS success, error_message;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `blog`
--

INSERT INTO `blog` (`id`, `title`, `image`, `date`, `description`, `category_id`, `content`, `created_by`, `type`) VALUES
(1, 'Why Summer is the Best Time to Visit Andaman Islands?', '../upload/blog073.webp', '2024-04-08 11:17:28', 'As the temperatures rise and the days grow longer, there’s no better time to escape to the tropical paradise of Andaman Islands than during the summer months. With its pristine beaches, crystal-clear waters, and an abundance of thrilling activities, Andaman beckons travelers to indulge in sun-kissed adventures unlike any other. Read on to know as we explore why summer is the perfect season to visit Andaman and discover the plethora of water activities awaiting you in this idyllic destination.', 2, '<h2><strong>Why Summer is the Best Time to Visit Andaman Islands?</strong></h2>\r\n\r\n<p>As the temperatures rise and the days grow longer, there&rsquo;s no better time to escape to the tropical paradise of Andaman Islands than during the summer months. With its pristine beaches, crystal-clear waters, and an abundance of thrilling activities, Andaman beckons travelers to indulge in sun-kissed adventures unlike any other. Read on to know as we explore why summer is the perfect season to visit Andaman and discover the plethora of water activities awaiting you in this idyllic destination.</p>\r\n\r\n<p>Summer in Andaman Islands is a time of balmy breezes, azure skies, and endless sunshine, making it the ideal season for beach lovers and water enthusiasts alike. With temperatures ranging from pleasantly warm to comfortably hot, summer offers the perfect climate for outdoor exploration and aquatic adventures. Plus, with fewer crowds compared to the peak tourist season, you&rsquo;ll have more space to unwind and soak in the natural beauty of this captivating archipelago.</p>\r\n\r\n<h2><strong>Thrilling Water Activities Await</strong></h2>\r\n\r\n<p>From heart-pounding water sports to serene underwater explorations, Andaman offers an array of exhilarating activities to suit every taste and preference. Here are some must-try water activities to add to your summer itinerary:</p>\r\n\r\n<h3><strong>1. Scuba Diving:</strong></h3>\r\n\r\n<p>Dive into the mesmerizing underwater world of Andaman and discover a kaleidoscope of colorful coral reefs, vibrant marine life, and fascinating underwater landscapes. With crystal-clear visibility and warm waters, summer provides the perfect conditions for unforgettable scuba diving experiences.</p>\r\n\r\n<h3><strong>2. Snorkeling:</strong></h3>\r\n\r\n<p>Explore the shallow reefs and tranquil lagoons of Andaman&rsquo;s coastline through snorkeling excursions, where you&rsquo;ll encounter an abundance of corals reefs, tropical fish, sea turtles, and other marine life. Summer&rsquo;s calm seas and gentle currents make it an ideal time for snorkeling enthusiasts of all skill levels.</p>\r\n\r\n<h3><strong>3. Game Fishing:</strong></h3>\r\n\r\n<p>Cast your line into the deep blue waters surrounding Andaman and embark on a thrilling game fishing adventure. Summer brings an abundance of pelagic species, including marlin, sailfish, and tuna, offering anglers the chance to reel in their prized catch amidst stunning ocean vistas.&nbsp;<a href=\"http://www.andamanislandspirit.com/\">Andaman Island Spirit Charters</a>&nbsp;have the best luxury boat to take you on these adventure.</p>\r\n\r\n<h3><strong>4. Fun Fishing:</strong></h3>\r\n\r\n<p>For a more leisurely fishing experience, embark on a fun fishing trip and try your hand at catching a variety of reef fish and inshore species. With the guidance of experienced local fishermen, you&rsquo;ll enjoy a relaxing day on the water while soaking in the beauty of Andaman&rsquo;s coastal scenery.</p>\r\n\r\n<h3><strong>Stay at SeaShell Hotels and Resorts for the Ultimate Vacation Experience</strong></h3>\r\n\r\n<p>Make your summer getaway even more memorable by staying at SeaShell Hotels and Resorts, where luxury meets comfort in the heart of Andaman Islands. Whether you prefer a beachfront villa overlooking the azure waters or a cozy room nestled amidst lush tropical gardens, SeaShell offers a range of accommodation options to suit your needs. Enjoy world-class amenities, delectable dining options, and personalized service, ensuring a truly unforgettable vacation experience.</p>\r\n\r\n<h3><strong>Plan Your Summer Escape to Andaman</strong></h3>\r\n\r\n<p>With its warm weather, clear skies, and endless opportunities for aquatic exploration, summer in Andaman Islands promises a vacation experience like no other. Whether you&rsquo;re seeking adrenaline-pumping adventures or peaceful moments of relaxation by the sea, Andaman has something for everyone to enjoy. So pack your sunscreen, dust off your swimsuit, and get ready to make unforgettable memories in the sun-drenched paradise of Andaman Islands this summer. Book your stay today Click&nbsp;<a href=\"https://seashellhotels.net/\">Here</a>.</p>\r\n', 'hxqduccopny@gmail.com', 0),
(2, 'Cách trang trí nhà đẹp nhất 2020 theo phong cách Urban', '../upload/blog5f6d8e130a18d06a4787d3ad_5f584854fe445e6f7187d8b0_phong-c25A1ch-urban-trong-n25BB25E125A5t-1-.png', '2024-04-10 02:42:52', 'Phong cách Urban trong nội thất là gì? Làm sao để thiết kế nhà đẹp phong cách Urban? Những mẫu tham khảo về phong cách Urban trong nội thất? Nếu bạn đang thắc mắc những điều trên thì hãy theo dõi bài viết này để có cho mình câu trả lời nhé!', 2, '<p>Trong&nbsp;<a href=\"https://useful.vn/cach-trang-tri-nha-dep-nhat-2020-theo-phong-cach-urban/\">thiết kế nội thất&nbsp;</a>ng&agrave;y nay, rất đa dạng về phong c&aacute;ch cũng như m&agrave;u sắc v&agrave; chất ri&ecirc;ng khi &aacute;p dụng v&agrave;o thiết kế nội thất.</p>\r\n\r\n<p>Đồng nghĩa, việc chọn ra một phong c&aacute;ch thiết kế nội thất cho căn nh&agrave; của m&igrave;nh cũng l&agrave; một kh&oacute; khăn đối với gia chủ?</p>\r\n\r\n<p><img alt=\"trang-tri-nha-dep-phong-cach-urban-1\" src=\"https://uploads-ssl.webflow.com/5f6d70089091119dc2fb973f/5f6d8e130a18d06a4787d3ad_5f584854fe445e6f7187d8b0_phong-c%2525C3%2525A1ch-urban-trong-n%2525E1%2525BB%252599i-th%2525E1%2525BA%2525A5t-1-.png\" /></p>\r\n\r\n<p>Ch&iacute;nh v&igrave; thế, mục ti&ecirc;u của b&agrave;i viết n&agrave;y l&agrave; để gi&uacute;p bạn hiểu hơn về phong c&aacute;ch Urban v&agrave; tips trang tr&iacute;&nbsp;<em>nh&agrave; đẹp phong c&aacute;ch Urban.</em></p>\r\n\r\n<h2><strong>Phong c&aacute;ch Urban l&agrave; g&igrave;?</strong></h2>\r\n\r\n<p><strong><em>Phong c&aacute;ch Urban</em></strong>&nbsp;được xem l&agrave; biến tấu của phong c&aacute;ch hiện đại, hay c&ograve;n c&oacute; thể gọi l&agrave; Phong c&aacute;ch hiện đại Urban. Ngo&agrave;i ra, Phong c&aacute;ch Urban c&ograve;n được kế thừa một số ưu điểm từ&nbsp;<strong><em>Phong c&aacute;ch Industrial</em></strong>&nbsp;với sự kết hợp giữa m&agrave;u kem v&agrave; c&agrave; ph&ecirc;, với lối thiết kế độc đ&aacute;o l&agrave;m nổi bật những m&oacute;n đồ đ&aacute;ng ch&uacute; &yacute;.</p>\r\n\r\n<p><img alt=\"\" src=\"https://uploads-ssl.webflow.com/5f6d70089091119dc2fb973f/5f6d8e12a14459b1cf233fb2_5f584855dd5fe9d2ee85264c_trang-tri-nha-dep-phong-cach-urban-2.png\" /></p>\r\n', 'hoangnhan@hcmussh.edu.vn', 0),
(3, 'Các loại đèn trang trí nội thất phổ biến từ cổ điển đến hiện đại', '../upload/blog5f6d8d53294b943adf5b44ed_5f5af5a3c5f7af29fa3c52b5_cac-loai-den-trang-tri-noi-that-pho-bien-nhat-3.jpeg', '2024-04-10 02:44:48', 'Trang trí nội thất không chỉ là vấn đề liên quan đến kiến trúc hay màu sắc, mà còn là cuộc chơi của ánh sáng. Bên cạnh ánh sáng tự nhiên, nếu vận dụng linh hoạt các loại đèn trang trí, bạn sẽ có thể tạo nên một không gian sống hoàn hảo nhất.\r\n\r\nCùng Blog Kênh Decor khám phá những loại đèn trang trí được sử dụng phổ biến nhất trong không gian nội thất. Từ đó, bạn sẽ có gợi ý để áp dụng vào căn nhà của mình.', 2, '<h2><strong>1. Đ&egrave;n tường</strong></h2>\r\n\r\n<p>Đ&egrave;n tường l&agrave; một trong&nbsp;<strong>c&aacute;c loại đ&egrave;n trang tr&iacute;</strong>&nbsp;được sử dụng thường xuy&ecirc;n trong kh&ocirc;ng gian nh&agrave; ở. Ngo&agrave;i t&aacute;c dụng sử dụng như đ&egrave;n chiếu s&aacute;ng khu vực cầu thang, c&aacute;c loại đ&egrave;n treo tường, đ&egrave;n ốp tường với thiết kế độc đ&aacute;o gi&uacute;p tạo điểm nhấn ấn tượng cho kh&ocirc;ng gian ph&ograve;ng kh&aacute;ch.</p>\r\n\r\n<p>Nhiều sản phẩm đ&egrave;n treo tường ng&agrave;y nay được cải tiến nhằm tăng thời gian sử dụng v&agrave; tiết kiệm điện tối đa. đ&egrave;n tường ph&ograve;ng kh&aacute;ch ngo&agrave;i việc bổ sung &aacute;nh s&aacute;ng b&ugrave; cho những khu vực đ&egrave;n chiếu s&aacute;ng kh&ocirc;ng chiếu tới, c&ograve;n c&oacute; thể sử dụng l&agrave;m đ&egrave;n chiếu s&aacute;ng v&agrave;o ban đ&ecirc;m.</p>\r\n\r\n<p><img alt=\"Đèn tường\" src=\"https://uploads-ssl.webflow.com/5f6d70089091119dc2fb973f/5f6d8d53a569d235fcc2a545_5f5af5a331b6f8e5efdeb8e4_cac-loai-den-trang-tri-noi-that-pho-bien-nhat-1.jpeg\" /></p>\r\n\r\n<p><em>source: ylighting.com</em></p>\r\n\r\n<p><strong>2. Đ&egrave;n ch&ugrave;m</strong>Nhắc đến&nbsp;<strong>c&aacute;c loại&nbsp;</strong><a href=\"https://useful.vn/cac-loai-den-trang-tri-noi-that-pho-bien-tu-co-dien-den-hien-dai/\"><strong>đ&egrave;n trang tr&iacute;</strong>&nbsp;nội thất</a>&nbsp;m&agrave; bỏ qua đ&egrave;n ch&ugrave;m th&igrave; thật sự l&agrave; một sự thiếu s&oacute;t lớn. C&oacute; nhiều phong c&aacute;ch đ&egrave;n ch&ugrave;m để bạn lựa chọn như: đ&egrave;n pha l&ecirc;, đ&egrave;n cổ điển ch&acirc;u &Acirc;u, đ&egrave;n ch&ugrave;m hiện đại v&agrave; đ&egrave;n ch&ugrave;m nghệ thuật. Đ&oacute; l&agrave; những sản phẩm s&aacute;ng tạo đa dạng về thiết kế, họa tiết v&agrave; c&aacute; t&iacute;nh.</p>\r\n\r\n<p>Đ&egrave;n ch&ugrave;m c&oacute; t&iacute;nh tập trung v&agrave; gi&aacute; trị thẩm mỹ cao. Trong kh&ocirc;ng gian ph&ograve;ng kh&aacute;ch, chỉ với một chiếc đ&egrave;n ch&ugrave;m ph&ugrave; hợp, bạn c&oacute; thể l&agrave;m nổi bật c&aacute; t&iacute;nh, gu thẩm mỹ v&agrave; phong c&aacute;ch sống của bản th&acirc;n.</p>\r\n\r\n<p><img alt=\"\" src=\"https://uploads-ssl.webflow.com/5f6d70089091119dc2fb973f/5f6d8d537a44a2019fabed56_5f5af5a4d3dde35c3d3c9892_cac-loai-den-trang-tri-noi-that-pho-bien-nhat-2.jpeg\" /></p>\r\n\r\n<p><em>source: elledecor.com</em></p>\r\n\r\n<h2><strong>3. Đ&egrave;n m&acirc;m ốp trần</strong></h2>\r\n\r\n<p>Nếu đ&egrave;n ch&ugrave;m đại diện cho&nbsp;<strong>c&aacute;c loại đ&egrave;n trang tr&iacute;&nbsp;</strong>cổ điển, đ&egrave;n m&acirc;m ch&iacute;nh l&agrave; đại diện cho đ&egrave;n trang tr&iacute; phong c&aacute;ch hiện đại. Đ&egrave;n m&acirc;m ốp trần v&ocirc; c&ugrave;ng đa dạng về k&iacute;ch thước, kiểu d&aacute;ng, ph&ugrave; hợp với nhiều kh&ocirc;ng gian v&agrave; phong c&aacute;ch thiết kế kh&aacute;c nhau.</p>\r\n\r\n<p>Đ&egrave;n m&acirc;m cũng c&oacute; nhiều loại m&agrave;u sắc &aacute;nh s&aacute;ng kh&aacute;c nhau, trong đ&oacute; &aacute;nh s&aacute;ng trắng v&agrave; &aacute;nh s&aacute;ng v&agrave;ng được sử dụng phổ biến nhất. Thậm ch&iacute;, c&oacute; loại đ&egrave;n c&ograve;n c&oacute; thể thay đổi m&agrave;u sắc &aacute;nh s&aacute;ng dễ d&agrave;ng nhằm đ&aacute;p ứng nhu cầu sử dụng tại những thời điểm kh&aacute;c nhau.</p>\r\n\r\n<p><img alt=\"Đèn mâm ốp trần\" src=\"https://uploads-ssl.webflow.com/5f6d70089091119dc2fb973f/5f6d8d53294b943adf5b44ed_5f5af5a3c5f7af29fa3c52b5_cac-loai-den-trang-tri-noi-that-pho-bien-nhat-3.jpeg\" /></p>\r\n\r\n<p><em>source: ylighting.com</em></p>\r\n', 'hoangnhan@hcmussh.edu.vn', 0),
(4, 'Phong cách Industrial – 6 bước trang trí nhà đẹp kiểu công nghiệp', '../upload/blog5f6d821699ea0546b835788a_5f5edbb6eee0ea5019affdc2_phong-cach-industrial-6-buoc-trang-tri-nha-dep-kieu-cong-nghiep-1.jpeg', '2024-04-10 02:47:19', 'Thời gian gần đây, phong cách Industrial dần trở nên phổ biến trong thiết kế – trang trí nội thất tại VIệt Nam. Bạn có thể bắt gặp phong cách này tại các co-working space, cửa hàng thời trang và trung tâm thương mại. Bạn cũng có thể áp dụng phong cách thiết kế độc đáo vào không gian nhà ở với hướng dẫn sau.', 2, '<h3><strong>1. Phong c&aacute;ch Industrial l&agrave; g&igrave;?</strong></h3>\r\n\r\n<p>C&aacute;i t&ecirc;n n&oacute;i l&ecirc;n tất cả,&nbsp;<strong>phong c&aacute;ch Industrial</strong>&nbsp;được lấy cảm hứng từ những thiết kế nh&agrave; xưởng c&ocirc;ng nghiệp, từ đ&oacute; &aacute;p dụng cho những c&ocirc;ng tr&igrave;nh kiến tr&uacute;c kh&aacute;c như: văn ph&ograve;ng, trung t&acirc;m thương mại hay nh&agrave; ở.</p>\r\n\r\n<p>Đ&acirc;y kh&ocirc;ng phải l&agrave; phong c&aacute;ch thiết kế qu&aacute; mới m&agrave; đ&atilde; xuất hiện từ đầu thế kỷ 20, thời kỳ tho&aacute;i tr&agrave;o của c&aacute;ch mạng c&ocirc;ng nghiệp. Thời đ&oacute;, c&aacute;c nước T&acirc;y &Acirc;u bắt đầu chuyển c&ocirc;ng xưởng đến c&aacute;c nước thuộc địa dẫn đến c&aacute;c nh&agrave; xưởng c&ocirc;ng nghiệp bị bỏ hoang. Những nh&agrave; xưởng trong khu d&acirc;n cư sau n&agrave;y được tận dụng để thi c&ocirc;ng khu t&aacute;i định cư, từ đ&oacute; h&igrave;nh th&agrave;nh n&ecirc;n một phong c&aacute;ch thiết kế nội thất kiểu &ldquo;c&ocirc;ng nghiệp&rdquo;.</p>\r\n\r\n<p>Một thiết kế nội thất theo phong c&aacute;ch c&ocirc;ng nghiệp được đặc trưng bởi: bức tường th&ocirc;, t&ocirc;ng m&agrave;u trầm, vật dụng nội thất tối giản, cầu thang kim loại v&agrave; điểm nhấn độc đ&aacute;o. Bằng c&aacute;ch sử dụng linh hoạt những yếu tố n&agrave;y, bạn c&oacute; thể kiến tạo một kh&ocirc;ng gian nh&agrave; ở độc đ&aacute;o, ph&aacute; c&aacute;ch.</p>\r\n\r\n<p><img alt=\"Phong cách Industrial\" src=\"https://uploads-ssl.webflow.com/5f6d70089091119dc2fb973f/5f6d821699ea0546b835788a_5f5edbb6eee0ea5019affdc2_phong-cach-industrial-6-buoc-trang-tri-nha-dep-kieu-cong-nghiep-1.jpeg\" /></p>\r\n\r\n<p><em>source: nextluxury.com</em></p>\r\n\r\n<h3><strong>2. Những bước trang tr&iacute; nh&agrave; theo phong c&aacute;ch Industrial</strong></h3>\r\n\r\n<h4><strong>Bước 1: Lựa chọn m&agrave;u sắc</strong></h4>\r\n\r\n<p>M&agrave;u sắc ch&iacute;nh l&agrave; nền tảng của&nbsp;<strong>phong c&aacute;ch Industrial</strong>. C&oacute; kh&aacute; nhiều lựa chọn cho bạn, nhưng về cơ bản nếu ng&ocirc;i nh&agrave; gồm những bức tường xi măng, s&agrave;n gỗ v&agrave; kim loại sơn đen th&igrave; thiết kế đạt 50% rồi đ&oacute;.</p>\r\n\r\n<p>Bảng m&agrave;u sử dụng bao gồm những m&agrave;u sắc thể hiện sự mộc mạc như: m&agrave;u trắng, m&agrave;u đen, m&agrave;u navy, m&agrave;u be v&agrave; m&agrave;u n&acirc;u. Bạn cũng c&oacute; nhiều lựa chọn nội thất như sofa da v&agrave; b&agrave;n ghế gỗ th&ocirc;.</p>\r\n\r\n<p><img alt=\"Phong cách Industrial\" src=\"https://uploads-ssl.webflow.com/5f6d70089091119dc2fb973f/5f6d821614ad497de57992d3_5f5edbb50bd0437e92cd1f2e_phong-cach-industrial-6-buoc-trang-tri-nha-dep-kieu-cong-nghiep-2.jpeg\" /></p>\r\n\r\n<p><em>source: mdesign.vn</em></p>\r\n\r\n<h4><strong>Bước 2: Trang tr&iacute; tường, trần v&agrave; s&agrave;n</strong></h4>\r\n\r\n<p>Phong c&aacute;ch c&ocirc;ng nghiệp được đặc trưng bởi những bức tường th&ocirc;, trần trơn v&agrave; s&agrave;n l&aacute;t gỗ. Tất cả nhằm mục đ&iacute;ch giả lập một c&ocirc;ng xưởng tuy đơn giản nhưng thu h&uacute;t.</p>\r\n\r\n<p>Những bức tường th&ocirc; tạo n&ecirc;n sự kh&aacute;c biệt độc đ&aacute;o cho căn nh&agrave;. Bạn c&oacute; thể thi c&ocirc;ng tường ốp gạch, ốp gỗ hoặc đơn giản l&agrave; tường b&ecirc; t&ocirc;ng m&agrave;i đều được.</p>\r\n\r\n<p>Trần n&ecirc;n được giữ cho đơn giản nhất c&oacute; thể. Bạn c&oacute; thể trang tr&iacute; trần bằng hệ thống ống dẫn kết hợp với đ&egrave;n ray led nhằm m&ocirc; tả kh&ocirc;ng gian b&ecirc;n trong một nh&agrave; xưởng c&ocirc;ng nghiệp.</p>\r\n\r\n<p>S&agrave;n n&ecirc;n sử dụng chất liệu như gỗ hoặc b&ecirc; t&ocirc;ng thay cho gạch men hiện đại. Bạn c&oacute; thể kết hợp theo kiểu s&agrave;n gỗ &ndash; tường b&ecirc; t&ocirc;ng hoặc ngược lại đều được.</p>\r\n\r\n<p><img alt=\"Phong cách Industrial\" src=\"https://uploads-ssl.webflow.com/5f6d70089091119dc2fb973f/5f6d82169bcba0017b61f12d_5f5edbb67557c162c9dfa6a8_phong-cach-industrial-6-buoc-trang-tri-nha-dep-kieu-cong-nghiep-3.jpeg\" /></p>\r\n\r\n<p><em>source: nextluxury.com</em></p>\r\n', 'hoangnhan@hcmussh.edu.vn', 0),
(11, 'Lịch sử hình thành', '', '2024-04-10 02:47:19', 'Công ty Sam Ry là một trong những công ty phát triển theo hướng sản xuất nút áo từ vỏ ốc xuất khẩu trong và ngoài nước. ', 10, '<p style=\"text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#ff0000\"><strong>C&ocirc;ng ty Sam Ry</strong></span><span style=\"color:#000000\"><strong> </strong>l&agrave; một trong những c&ocirc;ng ty ph&aacute;t triển theo hướng sản xuất n&uacute;t &aacute;o từ vỏ ốc xuất khẩu trong v&agrave; ngo&agrave;i nước. Tuy mới th&agrave;nh lập từ năm 2011, nhưng c&ocirc;ng ty đ&atilde; c&oacute; 10 năm l&agrave;m việc trong ng&agrave;nh v&agrave; trở th&agrave;nh thương hiệu quen thuộc, đối t&aacute;c tin cậy của nhiều bạn h&agrave;ng trong nước v&agrave; Quốc tế.&nbsp;</span></span></span></p>\r\n\r\n<p><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Với 4 năm hoạt động, c&ocirc;ng ty đ&atilde; c&oacute; một hệ thống kh&aacute;ch h&agrave;ng ổn định từ nhiều nước như </span><span style=\"color:#ff0000\"><strong>Ấn Độ, &Yacute;, H&agrave;n Quốc,....</strong></span><span style=\"color:#000000\"> Lu&ocirc;n lấy yếu tố </span><span style=\"color:#ff0000\"><strong>&ldquo; <em>Chất lượng l&agrave; h&agrave;ng đầu</em>&rdquo;</strong></span><span style=\"color:#000000\"> l&agrave;m nền tảng, C&ocirc;ng ty hiểu rằng, niềm tin của kh&aacute;ch h&agrave;ng về gi&aacute; th&agrave;nh, chất lượng của sản phẩm l&agrave; sự tồn tại của C&ocirc;ng ty. Do vậy, mọi hoạt động kinh doanh của C&ocirc;ng ty lu&ocirc;n hướng tới mục ti&ecirc;u bảo đảm v&agrave; ưu ti&ecirc;n chất lượng cho đối t&aacute;c. </span><br />\r\n<br />\r\n<span style=\"color:#ff0000\"><strong>Sam Ry</strong></span><span style=\"color:#000000\"> cũng mang lại cho c&ocirc;ng nh&acirc;n của c&ocirc;ng ty một m&ocirc;i trường l&agrave;m việc th&acirc;n thiện, c&ocirc;ng việc ổn định. Tất cả nh&acirc;n vi&ecirc;n đều l&agrave; những người d&acirc;n l&agrave;nh nghề </span><span style=\"color:#ff0000\"><strong>trải d&agrave;i từ nhiều độ tuổi</strong></span><span style=\"color:#000000\">. C&ocirc;ng ty đ&atilde; th&agrave;nh c&ocirc;ng trong việc tạo ra thu nhập ổn định cho b&agrave; con sống quanh đ&acirc;y. &nbsp; </span></span></span></p>\r\n\r\n<p><img alt=\"3264 Free CC0 Sea shells Stock Photos - StockSnap.io\" src=\"https://media.istockphoto.com/id/508578343/photo/seashell-on-the-beach.webp?b=1&amp;s=612x612&amp;w=0&amp;k=20&amp;c=0Nok9oThGdsjBtPvF1jMJVPL50aTDx5XVfkCQwOs2XM=\" /></p>\r\n\r\n<p><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\">Với việc kh&ocirc;ng ngừng ph&aacute;t triển v&agrave; cải tiến, <span style=\"color:#e74c3c\"><strong>Sam Ry</strong> </span>kh&ocirc;ng chỉ dừng lại ở việc sản xuất v&agrave; xuất khẩu n&uacute;t &aacute;o từ vỏ ốc, m&agrave; c&ograve;n mở rộng dịch vụ v&agrave; sản phẩm li&ecirc;n quan đến ng&agrave;nh thời trang, đ&aacute;p ứng nhu cầu đa dạng của kh&aacute;ch h&agrave;ng. C&ocirc;ng ty lu&ocirc;n đầu tư v&agrave;o nghi&ecirc;n cứu v&agrave; ph&aacute;t triển c&ocirc;ng nghệ mới, từ đ&oacute; kh&ocirc;ng ngừng đưa ra những sản phẩm độc đ&aacute;o v&agrave; đẳng cấp, đồng thời giữ vững uy t&iacute;n v&agrave; chất lượng h&agrave;ng đầu tr&ecirc;n thị trường.</span></span></p>\r\n\r\n<p><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\">Để đạt được th&agrave;nh c&ocirc;ng như hiện nay, Sam Ry đ&atilde; kh&ocirc;ng ngừng đ&agrave;o tạo v&agrave; n&acirc;ng cao tr&igrave;nh độ chuy&ecirc;n m&ocirc;n cho đội ngũ nh&acirc;n vi&ecirc;n, gi&uacute;p họ lu&ocirc;n cập nhật với c&aacute;c xu hướng mới nhất v&agrave; phản &aacute;nh nhanh ch&oacute;ng v&agrave;o quy tr&igrave;nh sản xuất.</span></span></p>\r\n\r\n<p><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\">Kh&ocirc;ng chỉ l&agrave; một doanh nghiệp kinh doanh, Sam Ry c&ograve;n đ&oacute;ng g&oacute;p t&iacute;ch cực v&agrave;o c&aacute;c hoạt động x&atilde; hội v&agrave; bảo vệ m&ocirc;i trường. C&ocirc;ng ty lu&ocirc;n hướng đến việc sản xuất v&agrave; kinh doanh bền vững, bảo vệ nguồn t&agrave;i nguy&ecirc;n thi&ecirc;n nhi&ecirc;n v&agrave; g&oacute;p phần giảm thiểu t&aacute;c động ti&ecirc;u cực đến m&ocirc;i trường.</span></span></p>\r\n\r\n<p><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\">Với tất cả những nỗ lực v&agrave; cam kết, Sam Ry đ&atilde; v&agrave; đang kh&ocirc;ng ngừng n&acirc;ng cao vị thế của m&igrave;nh tr&ecirc;n <strong><span style=\"color:#e74c3c\">thị trường quốc tế</span></strong>, đồng thời khẳng định được sự độc đ&aacute;o v&agrave; uy t&iacute;n trong ng&agrave;nh sản xuất n&uacute;t &aacute;o từ vỏ ốc.</span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n', 'hxqduccopny@gmail.com', 1),
(12, 'Giới thiệu về Samry', '', '2024-04-10 02:47:19', 'Hiện nay, nút áo từ vỏ ốc đang là một trong những sản phẩm nổi trội được nhiều người quan tâm tìm hiểu. Ngoài những sự lựa chọn hiện có trên thị trường, quý khách hàng có thể đến với công ty Sam Ry để cảm nhận tuyệt vời về sản phẩm nút áo vỏ ốc của công ty. ', 10, '<h3><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><strong>1. Điều g&igrave; gợi nhớ về SamryShell?</strong></span></span></span></h3>\r\n\r\n<p><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\">Hiện nay, n&uacute;t &aacute;o từ vỏ ốc đang l&agrave; một trong những sản phẩm nổi trội được nhiều người quan t&acirc;m t&igrave;m hiểu. Ngo&agrave;i những sự lựa chọn hiện c&oacute; tr&ecirc;n thị trường, qu&yacute; kh&aacute;ch h&agrave;ng c&oacute; thể đến với c&ocirc;ng ty Sam Ry để cảm nhận tuyệt vời về sản phẩm n&uacute;t &aacute;o vỏ ốc của c&ocirc;ng ty.&nbsp;</span></span></span></p>\r\n\r\n<p><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\">Được th&agrave;nh lập từ năm 20XX cho đến nay c&ocirc;ng ty ch&uacute;ng t&ocirc;i đ&atilde; ng&agrave;y c&agrave;ng ph&aacute;t triển, ng&agrave;y c&agrave;ng được kh&aacute;ch h&agrave;ng ưa chuộng. Trong thời gian sắp tới c&ocirc;ng ty đang phấn đấu để ho&agrave;n thiện mục ti&ecirc;u sẽ trở th&agrave;nh một trong số những c&ocirc;ng ty đi đầu về sản xuất n&uacute;t &aacute;o vỏ ốc cho kh&aacute;ch h&agrave;ng trong v&agrave; ngo&agrave;i nước.&nbsp;</span></span></span></p>\r\n\r\n<p><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\">Với sứ mệnh đem lại n&uacute;t &aacute;o từ vỏ ốc chất lượng nhất,&nbsp; Sam Ry lu&ocirc;n li&ecirc;n tục đổi mới v&agrave; ph&aacute;t triển kh&ocirc;ng ngừng nghỉ. Với tinh thần s&aacute;ng tạo v&agrave; cam kết kh&ocirc;ng ngừng, c&ocirc;ng ty Sam Ry hy vọng sẽ được hợp t&aacute;c với kh&aacute;ch h&agrave;ng trong v&agrave; ngo&agrave;i nước, đem lại sự uy t&iacute;n v&agrave; chất lượng nhất c&oacute; thể.&nbsp; </span></span></span></p>\r\n\r\n<p><img alt=\"Seashell Wall Hanging : 6 Steps (with Pictures) - Instructables\" src=\"https://content.instructables.com/FWK/R7HY/KQ6MOIYJ/FWKR7HYKQ6MOIYJ.jpg?auto=webp&amp;fit=bounds&amp;frame=1&amp;height=1024&amp;width=1024auto=webp&amp;frame=1&amp;height=150\" style=\"height:50%\" /></p>\r\n\r\n<h3><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><strong>2. Tại sao bạn n&ecirc;n lựa chọn n&uacute;t &aacute;o vỏ ốc của Sam Ry?</strong></span></span></span></h3>\r\n\r\n<p><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\">Sản phẩm n&uacute;t &aacute;o từ vỏ ốc của Sam Ry kh&ocirc;ng chỉ được biết đến với chất lượng tuyệt vời m&agrave; c&ograve;n mang đến một phong c&aacute;ch độc đ&aacute;o v&agrave; tinh tế. Ch&uacute;ng t&ocirc;i lu&ocirc;n ch&uacute; trọng v&agrave;o việc lựa chọn nguy&ecirc;n liệu cao cấp v&agrave; quy tr&igrave;nh sản xuất ti&ecirc;n tiến để đảm bảo mỗi sản phẩm đều đạt được ti&ecirc;u chuẩn cao nhất.</span></span></span></p>\r\n\r\n<p><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\">B&ecirc;n cạnh đ&oacute;, đội ngũ nh&acirc;n vi&ecirc;n của Sam Ry được đ&agrave;o tạo chuy&ecirc;n nghiệp v&agrave; tận t&acirc;m, lu&ocirc;n sẵn l&ograve;ng lắng nghe v&agrave; tư vấn cho kh&aacute;ch h&agrave;ng những lựa chọn ph&ugrave; hợp nhất. Điều n&agrave;y gi&uacute;p ch&uacute;ng t&ocirc;i kh&ocirc;ng chỉ đ&aacute;p ứng nhu cầu của kh&aacute;ch h&agrave;ng m&agrave; c&ograve;n vượt qua mong đợi của họ.</span></span></span></p>\r\n\r\n<p><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\">Đặc biệt, Sam Ry cũng mang đến nhiều ưu đ&atilde;i hấp dẫn v&agrave; ch&iacute;nh s&aacute;ch bảo h&agrave;nh d&agrave;nh ri&ecirc;ng cho kh&aacute;ch h&agrave;ng, gi&uacute;p họ cảm nhận được gi&aacute; trị v&agrave; sự tin tưởng khi chọn lựa sản phẩm của ch&uacute;ng t&ocirc;i.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h3><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><strong>3. Li&ecirc;n hệ với ch&uacute;ng t&ocirc;i</strong></span></span></span></h3>\r\n\r\n<p><span style=\"color:#000000\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\">Nếu bạn quan t&acirc;m v&agrave; muốn biết th&ecirc;m về sản phẩm n&uacute;t &aacute;o vỏ ốc của ch&uacute;ng t&ocirc;i, đừng ngần ngại li&ecirc;n hệ với đội ngũ tư vấn chuy&ecirc;n nghiệp của Sam Ry. Ch&uacute;ng t&ocirc;i lu&ocirc;n sẵn l&ograve;ng hỗ trợ v&agrave; giải đ&aacute;p mọi thắc mắc của bạn, đảm bảo rằng bạn sẽ c&oacute; trải nghiệm mua sắm tốt nhất. H&atilde;y đến với Sam Ry - nơi bạn t&igrave;m thấy sự ho&agrave;n hảo cho n&uacute;t &aacute;o của m&igrave;nh!</span></span></span></p>\r\n\r\n<h3>&nbsp;</h3>\r\n', 'hxqduccopny@gmail.com', 1),
(13, 'Xem công xưởng', '', '2024-04-10 02:47:19', 'Chúng tôi hân hạnh giới thiệu đến quý khách hàng và đối tác Phân Xưởng Sản Xuất của công ty- một trong những phân xưởng quan trọng của Công ty Sam Ry, chuyên về sản xuất sản phẩm nút áo từ vỏ ốc.', 10, '<h3 style=\"margin-left:80px; text-align:center\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><strong>Giới Thiệu Ph&acirc;n Xưởng Sản Xuất c&ocirc;ng ty Sam Ry</strong></span></span></span></h3>\r\n\r\n<h3 style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i h&acirc;n hạnh giới thiệu đến qu&yacute; kh&aacute;ch h&agrave;ng v&agrave; đối t&aacute;c Ph&acirc;n Xưởng Sản Xuất <span style=\"background-color:#ffffff\">của c&ocirc;ng ty-</span> một trong những ph&acirc;n xưởng quan trọng của C&ocirc;ng ty Sam Ry, chuy&ecirc;n về sản xuất sản phẩm n&uacute;t &aacute;o từ vỏ ốc.</span></span></span></h3>\r\n\r\n<h3 style=\"margin-left:120px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><strong>1. Chức năng v&agrave; vai tr&ograve;:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ph&acirc;n Xưởng Sản Xuất<span style=\"background-color:#ffffff\"> ABC</span> chịu tr&aacute;ch nhiệm sản xuất v&agrave; cung ứng c&aacute;c sản phẩm cắt v&agrave; tạo ra n&uacute;t &aacute;o th&ocirc; từ vỏ ốc.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ph&acirc;n xưởng đ&oacute;ng vai tr&ograve; quan trọng trong việc đảm bảo chuỗi cung ứng liền mạch v&agrave; đ&aacute;p ứng nhu cầu sản phẩm cho kh&aacute;ch h&agrave;ng.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:120px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><strong>2. Sản phẩm sản xuất:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i sản xuất c&aacute;c loại n&uacute;t &aacute;o từ vỏ ốc như&hellip;.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Sản phẩm của ch&uacute;ng t&ocirc;i được kiểm tra nghi&ecirc;m ngặt về chất lượng để đảm bảo đ&aacute;p ứng c&aacute;c ti&ecirc;u chuẩn cao nhất v&agrave; mang lại sự h&agrave;i l&ograve;ng cho kh&aacute;ch h&agrave;ng.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:120px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><strong>3. Quy tr&igrave;nh l&agrave;m việc:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><span style=\"background-color:#ffffff\">Ph&acirc;n Xưởng Sản Xuất ABC &aacute;</span>p dụng quy tr&igrave;nh sản xuất hiện đại v&agrave; tự động h&oacute;a, đảm bảo hiệu suất cao v&agrave; chất lượng ổn định.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i tu&acirc;n thủ c&aacute;c ti&ecirc;u chuẩn an to&agrave;n v&agrave; vệ sinh nghi&ecirc;m ngặt trong qu&aacute; tr&igrave;nh sản xuất.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:120px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><strong>4. Đội ngũ nh&acirc;n sự:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Đội ngũ nh&acirc;n vi&ecirc;n của ph&acirc;n xưởng bao gồm những kỹ sư v&agrave; c&ocirc;ng nh&acirc;n c&oacute; kinh nghiệm v&agrave; chuy&ecirc;n m&ocirc;n cao.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i cam kết tạo điều kiện l&agrave;m việc an to&agrave;n v&agrave; ph&aacute;t triển chuy&ecirc;n m&ocirc;n cho nh&acirc;n vi&ecirc;n.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:120px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><strong>5. Cơ sở vật chất v&agrave; trang thiết bị:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ph&acirc;n xưởng được trang bị m&aacute;y m&oacute;c v&agrave; c&ocirc;ng nghệ ti&ecirc;n tiến, cho ph&eacute;p sản xuất hiệu quả v&agrave; ch&iacute;nh x&aacute;c.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i thường xuy&ecirc;n đầu tư v&agrave;o c&ocirc;ng nghệ mới để n&acirc;ng cao năng suất v&agrave; chất lượng sản phẩm.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><img alt=\"World\'s Largest Shell Factory: world record in Fort Myers, Florida\" src=\"https://irp.cdn-website.com/08d31351/dms3rep/multi/422420-1-worlds-largest-shell-factory-fort-myers-392c365c.jpg\" style=\"height:100%; width:50%\" /></p>\r\n\r\n<h3 style=\"margin-left:120px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><strong>6. Đ&oacute;ng g&oacute;p cho c&ocirc;ng ty v&agrave; ng&agrave;nh c&ocirc;ng nghiệp:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ph&acirc;n xưởng đ&oacute;ng vai tr&ograve; quan trọng trong việc th&uacute;c đẩy sự ph&aacute;t triển của c&ocirc;ng ty v&agrave; n&acirc;ng cao vị thế của c&ocirc;ng ty tr&ecirc;n thị trường.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i lu&ocirc;n nỗ lực cải tiến v&agrave; đổi mới để đ&oacute;ng g&oacute;p v&agrave;o sự ph&aacute;t triển bền vững của ng&agrave;nh c&ocirc;ng nghiệp.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:120px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><strong>7. Tầm nh&igrave;n tương lai:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><span style=\"background-color:#ffffff\">Ph&acirc;n Xưởng Sản Xuất ABC</span> c&oacute; kế hoạch mở rộng quy m&ocirc; v&agrave; n&acirc;ng cao c&ocirc;ng suất sản xuất để đ&aacute;p ứng nhu cầu ng&agrave;y c&agrave;ng tăng của thị trường.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i sẽ tiếp tục đầu tư v&agrave;o c&ocirc;ng nghệ mới v&agrave; n&acirc;ng cao tr&igrave;nh độ chuy&ecirc;n m&ocirc;n của nh&acirc;n vi&ecirc;n.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:120px\"><strong><span style=\"font-size:14px\">Cuối c&ugrave;ng&nbsp;</span></strong></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\"><span style=\"background-color:#ffffff\">Ph&acirc;n Xưởng Sản Xuất ABC</span> cam kết mang lại những sản phẩm chất lượng cao v&agrave; dịch vụ tốt nhất cho kh&aacute;ch h&agrave;ng v&agrave; đối t&aacute;c. Ch&uacute;ng t&ocirc;i mong muốn tiếp tục đồng h&agrave;nh c&ugrave;ng qu&yacute; vị trong h&agrave;nh tr&igrave;nh ph&aacute;t triển v&agrave; th&agrave;nh c&ocirc;ng.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#0d0d0d\">H&atilde;y li&ecirc;n hệ với ch&uacute;ng t&ocirc;i để biết th&ecirc;m th&ocirc;ng tin hoặc sắp xếp một chuyến tham quan ph&acirc;n xưởng. Ch&uacute;ng t&ocirc;i rất mong được hợp t&aacute;c với qu&yacute; vị!</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px; text-align:justify\"><span style=\"font-family:Arial,Helvetica,sans-serif\"><span style=\"font-size:14px\"><span style=\"color:#000000\">Tự h&agrave;o với hệ thống m&aacute;y m&oacute;c chuy&ecirc;n nghiệp v&agrave; an to&agrave;n sẽ mang đến sản phẩm chất lượng nhằm đ&aacute;p ứng nhu cầu của qu&yacute; kh&aacute;ch h&agrave;ng trong v&agrave; ngo&agrave;i nước. </span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n', 'hxqduccopny@gmail.com', 1),
(14, 'Cơ cấu tổ chức', '', '2024-04-10 02:47:19', 'Cơ cấu tổ chức công ty Samry Shell', 10, '<p style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#e74c3c\">C&ocirc;ng ty Samry</span><span style=\"color:#0d0d0d\"> được tổ chức th&agrave;nh nhiều bộ phận v&agrave; ph&ograve;ng ban kh&aacute;c nhau, mỗi bộ phận c&oacute; vai tr&ograve; v&agrave; tr&aacute;ch nhiệm ri&ecirc;ng biệt, đ&oacute;ng g&oacute;p v&agrave;o sự th&agrave;nh c&ocirc;ng của to&agrave;n c&ocirc;ng ty. </span></span></span></p>\r\n\r\n<p style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Dưới đ&acirc;y l&agrave; cơ cấu tổ chức của c&ocirc;ng ty:</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>1. Ban Gi&aacute;m Đốc:</strong></span></span></span></h3>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Ban Gi&aacute;m Đốc chịu tr&aacute;ch nhiệm quản l&yacute; tổng thể v&agrave; ra quyết định chiến lược cho c&ocirc;ng ty.</span></span></span></p>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Gồm c&aacute;c vị tr&iacute; như Gi&aacute;m Đốc Điều H&agrave;nh (CEO), Gi&aacute;m Đốc T&agrave;i Ch&iacute;nh (CFO), Gi&aacute;m Đốc Kỹ Thuật (CTO), Gi&aacute;m Đốc Tiếp Thị (CMO), v&agrave; c&aacute;c gi&aacute;m đốc bộ phận kh&aacute;c.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>2. Ph&ograve;ng Nh&acirc;n Sự:</strong></span></span></span></h3>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Chịu tr&aacute;ch nhiệm tuyển dụng, đ&agrave;o tạo, quản l&yacute; nh&acirc;n vi&ecirc;n v&agrave; thực hiện c&aacute;c ch&iacute;nh s&aacute;ch nh&acirc;n sự.</span></span></span></p>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Đảm bảo m&ocirc;i trường l&agrave;m việc t&iacute;ch cực v&agrave; ph&aacute;t triển năng lực cho nh&acirc;n vi&ecirc;n.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>3. Ph&ograve;ng T&agrave;i Ch&iacute;nh:</strong></span></span></span></h3>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Quản l&yacute; t&agrave;i ch&iacute;nh của c&ocirc;ng ty, bao gồm ng&acirc;n s&aacute;ch, kế to&aacute;n, thu chi v&agrave; b&aacute;o c&aacute;o t&agrave;i ch&iacute;nh.</span></span></span></p>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Đảm bảo tu&acirc;n thủ c&aacute;c quy định ph&aacute;p luật về t&agrave;i ch&iacute;nh.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>4. Ph&ograve;ng Sản Xuất:</strong></span></span></span></h3>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Chịu tr&aacute;ch nhiệm sản xuất sản phẩm v&agrave; dịch vụ theo kế hoạch.</span></span></span></p>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Đảm bảo chất lượng sản phẩm v&agrave; tiến độ sản xuất.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>5. Ph&ograve;ng Kinh Doanh v&agrave; Tiếp Thị:</strong></span></span></span></h3>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Quản l&yacute; hoạt động b&aacute;n h&agrave;ng, tiếp thị, v&agrave; quan hệ kh&aacute;ch h&agrave;ng.</span></span></span></p>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Ph&aacute;t triển chiến lược tiếp thị v&agrave; mở rộng thị trường cho c&ocirc;ng ty.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>6. Ph&ograve;ng Nghi&ecirc;n Cứu v&agrave; Ph&aacute;t Triển (R&amp;D):</strong></span></span></span></h3>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Tập trung v&agrave;o nghi&ecirc;n cứu, đổi mới, v&agrave; ph&aacute;t triển sản phẩm mới.</span></span></span></p>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Hợp t&aacute;c với c&aacute;c bộ phận kh&aacute;c để cải thiện sản phẩm v&agrave; dịch vụ.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>7. Ph&ograve;ng H&agrave;nh Ch&iacute;nh:</strong></span></span></span></h3>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Quản l&yacute; c&aacute;c hoạt động h&agrave;nh ch&iacute;nh v&agrave; hậu cần của c&ocirc;ng ty.</span></span></span></p>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Đảm bảo hoạt động h&agrave;ng ng&agrave;y diễn ra su&ocirc;n sẻ.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>8. Ph&ograve;ng C&ocirc;ng Nghệ Th&ocirc;ng Tin:</strong></span></span></span></h3>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Quản l&yacute; hệ thống c&ocirc;ng nghệ th&ocirc;ng tin của c&ocirc;ng ty, bao gồm phần cứng, phần mềm, v&agrave; an ninh mạng.</span></span></span></p>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Hỗ trợ c&aacute;c bộ phận kh&aacute;c trong việc sử dụng c&ocirc;ng nghệ hiệu quả.</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>9. C&aacute;c Bộ Phận Chuy&ecirc;n M&ocirc;n Kh&aacute;c:</strong></span></span></span></h3>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">C&oacute; thể bao gồm c&aacute;c bộ phận như ph&ograve;ng ph&aacute;p l&yacute;, ph&ograve;ng an to&agrave;n lao động, ph&ograve;ng kiểm so&aacute;t chất lượng, </span></span></span></p>\r\n\r\n<p style=\"list-style-type:disc; margin-left:40px; text-align:justify\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">v&agrave; c&aacute;c bộ phận kh&aacute;c t&ugrave;y theo ng&agrave;nh nghề v&agrave; </span></span></span><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">quy m&ocirc; của c&ocirc;ng ty.</span></span></span></p>\r\n', 'hxqduccopny@gmail.com', 1);
INSERT INTO `blog` (`id`, `title`, `image`, `date`, `description`, `category_id`, `content`, `created_by`, `type`) VALUES
(15, 'Chính sách bán hàng', '', '2024-04-10 02:47:19', 'Chúng tôi trân trọng quý khách hàng và cam kết cung cấp sản phẩm chất lượng cao. Dưới đây là các điều khoản và điều kiện của chính sách bán hàng của chúng tôi:', 10, '<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i tr&acirc;n trọng qu&yacute; kh&aacute;ch h&agrave;ng v&agrave; cam kết cung cấp sản phẩm chất lượng cao. Dưới đ&acirc;y l&agrave; c&aacute;c điều khoản v&agrave; điều kiện của ch&iacute;nh s&aacute;ch b&aacute;n h&agrave;ng của ch&uacute;ng t&ocirc;i:</span></span></span></p>\r\n\r\n<h3 style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>1. Gi&aacute; cả v&agrave; Thanh to&aacute;n:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Gi&aacute; cả:</strong> Gi&aacute; sản phẩm được ni&ecirc;m yết<span style=\"background-color:#ffffff\"> </span></span><strong><span style=\"color:#e74c3c\"><span style=\"background-color:#ffffff\">tr&ecirc;n trang web</span></span></strong><span style=\"color:#0d0d0d\"> hoặc trong t&agrave;i liệu b&aacute;n h&agrave;ng của ch&uacute;ng t&ocirc;i. Gi&aacute; n&agrave;y c&oacute; thể thay đổi m&agrave; kh&ocirc;ng th&ocirc;ng b&aacute;o trước.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Thuế:</strong> Gi&aacute; sản phẩm chưa bao gồm thuế gi&aacute; trị gia tăng (VAT) hoặc c&aacute;c khoản thuế kh&aacute;c (nếu c&oacute;). Những khoản n&agrave;y sẽ được t&iacute;nh th&ecirc;m v&agrave;o h&oacute;a đơn.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Phương thức thanh to&aacute;n:</strong> Ch&uacute;ng t&ocirc;i chấp nhận c&aacute;c phương thức thanh to&aacute;n như chuyển khoản ng&acirc;n h&agrave;ng, thẻ t&iacute;n dụng, v&agrave; c&aacute;c phương thức kh&aacute;c theo thỏa thuận.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Thời hạn thanh to&aacute;n: Kh&aacute;ch h&agrave;ng cần thanh to&aacute;n đầy đủ trước khi giao h&agrave;ng hoặc theo thỏa thuận cụ thể.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\">&nbsp;</p>\r\n\r\n<h3 style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>2. Vận chuyển v&agrave; Giao nhận:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Ph&iacute; vận chuyển:</strong> Ph&iacute; vận chuyển sẽ được t&iacute;nh dựa tr&ecirc;n địa điểm giao h&agrave;ng v&agrave; trọng lượng của đơn h&agrave;ng.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Thời gian giao h&agrave;ng: </strong>Thời gian giao h&agrave;ng ước t&iacute;nh sẽ được th&ocirc;ng b&aacute;o cho kh&aacute;ch h&agrave;ng khi x&aacute;c nhận đơn h&agrave;ng. Ch&uacute;ng t&ocirc;i cố gắng giao h&agrave;ng đ&uacute;ng thời gian đ&atilde; cam kết.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Tr&aacute;ch nhiệm trong qu&aacute; tr&igrave;nh giao h&agrave;ng:</strong> Ch&uacute;ng t&ocirc;i chịu tr&aacute;ch nhiệm về h&agrave;ng h&oacute;a trong qu&aacute; tr&igrave;nh vận chuyển cho đến khi giao h&agrave;ng th&agrave;nh c&ocirc;ng cho kh&aacute;ch h&agrave;ng.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\">&nbsp;</p>\r\n\r\n<h3 style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>3. Bảo h&agrave;nh v&agrave; Ch&iacute;nh s&aacute;ch đổi trả:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Bảo h&agrave;nh:</strong> Sản phẩm của ch&uacute;ng t&ocirc;i được bảo h&agrave;nh theo thời gian v&agrave; điều kiện cụ thể được quy định trong t&agrave;i liệu b&aacute;n h&agrave;ng hoặc tr&ecirc;n trang web của ch&uacute;ng t&ocirc;i.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Đổi trả: </strong>Kh&aacute;ch h&agrave;ng c&oacute; thể đổi trả sản phẩm trong thời gian quy định (v&iacute; dụ: 7 ng&agrave;y hoặc 14 ng&agrave;y) nếu sản phẩm c&ograve;n nguy&ecirc;n trạng v&agrave; chưa qua sử dụng.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Quy tr&igrave;nh đổi trả: </strong>Kh&aacute;ch h&agrave;ng cần li&ecirc;n hệ với ch&uacute;ng t&ocirc;i để được hướng dẫn cụ thể về quy tr&igrave;nh đổi trả.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\">&nbsp;</p>\r\n\r\n<h3 style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>4. Ch&iacute;nh s&aacute;ch bảo mật th&ocirc;ng tin:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i cam kết bảo mật th&ocirc;ng tin c&aacute; nh&acirc;n của kh&aacute;ch h&agrave;ng v&agrave; chỉ sử dụng th&ocirc;ng tin đ&oacute; cho mục đ&iacute;ch xử l&yacute; đơn h&agrave;ng v&agrave; cung cấp dịch vụ.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\">&nbsp;</p>\r\n\r\n<h3 style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>5. Giải quyết tranh chấp:</strong></span></span></span></h3>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i lu&ocirc;n cố gắng giải quyết mọi tranh chấp một c&aacute;ch th&acirc;n thiện v&agrave; nhanh ch&oacute;ng.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Nếu kh&ocirc;ng thể đạt được thỏa thuận, c&aacute;c b&ecirc;n sẽ thực hiện theo quy định của ph&aacute;p luật hiện h&agrave;nh.</span></span></span></p>\r\n\r\n<h3>&nbsp;</h3>\r\n', 'hxqduccopny@gmail.com', 1),
(16, 'Chính sách vận chuyển', '', '2024-04-10 02:47:19', 'Chính sách vận chuyển của chúng tôi được thiết kế nhằm đảm bảo rằng hàng hóa được giao đến quý khách hàng một cách nhanh chóng, an toàn và hiệu quả. Dưới đây là các điều khoản và điều kiện về vận chuyển', 10, '<p style=\"margin-left:40px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Ch&iacute;nh s&aacute;ch vận chuyển của ch&uacute;ng t&ocirc;i được thiết kế nhằm đảm bảo rằng h&agrave;ng h&oacute;a được giao đến qu&yacute; kh&aacute;ch h&agrave;ng một c&aacute;ch nhanh ch&oacute;ng, an to&agrave;n v&agrave; hiệu quả. Dưới đ&acirc;y l&agrave; c&aacute;c điều khoản v&agrave; điều kiện về vận chuyển:</span></span></span></p>\r\n\r\n<p style=\"margin-left:40px\"><img alt=\"Shipping Policy - Vrindawan University\" src=\"https://i0.wp.com/vuniversity.in/wp-content/uploads/2022/08/04.jpg\" /></p>\r\n\r\n<h2 style=\"margin-left:40px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\"><strong>1. Ph&iacute; vận chuyển:</strong></span></span></span></h2>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Ph&iacute; vận chuyển sẽ được t&iacute;nh dựa tr&ecirc;n địa điểm giao h&agrave;ng, k&iacute;ch thước v&agrave; trọng lượng của đơn h&agrave;ng.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i c&oacute; thể miễn ph&iacute; vận chuyển đối với đơn h&agrave;ng c&oacute; gi&aacute; trị từ một mức nhất định trở l&ecirc;n.</span></span></span></p>\r\n\r\n<h2 style=\"margin-left:40px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\"><strong>2. Phương thức vận chuyển:</strong></span></span></span></h2>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i sử dụng c&aacute;c dịch vụ vận chuyển uy t&iacute;n để đảm bảo h&agrave;ng h&oacute;a được giao h&agrave;ng nhanh ch&oacute;ng v&agrave; an to&agrave;n.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Kh&aacute;ch h&agrave;ng c&oacute; thể chọn phương thức vận chuyển theo ưu ti&ecirc;n của m&igrave;nh, như giao h&agrave;ng nhanh, giao h&agrave;ng ti&ecirc;u chuẩn, hoặc phương thức kh&aacute;c nếu c&oacute;.</span></span></span></p>\r\n\r\n<h2 style=\"margin-left:40px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\"><strong>3. Thời gian giao h&agrave;ng:</strong></span></span></span></h2>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Thời gian giao h&agrave;ng sẽ được th&ocirc;ng b&aacute;o khi x&aacute;c nhận đơn h&agrave;ng v&agrave; t&ugrave;y thuộc v&agrave;o phương thức vận chuyển đ&atilde; chọn.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Thời gian giao h&agrave;ng c&oacute; thể thay đổi do c&aacute;c yếu tố ngo&agrave;i tầm kiểm so&aacute;t của ch&uacute;ng t&ocirc;i, như thời tiết xấu hoặc c&aacute;c sự cố vận chuyển.</span></span></span></p>\r\n\r\n<h2 style=\"margin-left:40px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\"><strong>4. Theo d&otilde;i đơn h&agrave;ng:</strong></span></span></span></h2>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i cung cấp th&ocirc;ng tin theo d&otilde;i đơn h&agrave;ng cho kh&aacute;ch h&agrave;ng để họ c&oacute; thể cập nhật trạng th&aacute;i giao h&agrave;ng.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Kh&aacute;ch h&agrave;ng c&oacute; thể theo d&otilde;i đơn h&agrave;ng th&ocirc;ng qua số theo d&otilde;i được cung cấp sau khi đơn h&agrave;ng được xuất xưởng.</span></span></span></p>\r\n\r\n<h2 style=\"margin-left:40px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\"><strong>5. Tr&aacute;ch nhiệm trong qu&aacute; tr&igrave;nh vận chuyển:</strong></span></span></span></h2>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i chịu tr&aacute;ch nhiệm về h&agrave;ng h&oacute;a trong qu&aacute; tr&igrave;nh vận chuyển cho đến khi giao h&agrave;ng th&agrave;nh c&ocirc;ng cho kh&aacute;ch h&agrave;ng.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Trong trường hợp h&agrave;ng h&oacute;a bị hư hỏng hoặc mất m&aacute;t trong qu&aacute; tr&igrave;nh vận chuyển, ch&uacute;ng t&ocirc;i sẽ l&agrave;m việc với kh&aacute;ch h&agrave;ng để giải quyết vấn đề.</span></span></span></p>\r\n\r\n<h2 style=\"margin-left:40px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\"><strong>6. Nhận h&agrave;ng:</strong></span></span></span></h2>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Kh&aacute;ch h&agrave;ng cần kiểm tra h&agrave;ng h&oacute;a ngay khi nhận h&agrave;ng. Nếu c&oacute; bất kỳ vấn đề g&igrave;, kh&aacute;ch h&agrave;ng cần th&ocirc;ng b&aacute;o cho ch&uacute;ng t&ocirc;i ngay lập tức.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i sẽ hướng dẫn kh&aacute;ch h&agrave;ng c&aacute;ch xử l&yacute; trong trường hợp nhận được h&agrave;ng h&oacute;a bị hư hỏng hoặc kh&ocirc;ng đ&uacute;ng với đơn đặt h&agrave;ng.</span></span></span></p>\r\n\r\n<h2 style=\"margin-left:40px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\"><strong>7. Giao h&agrave;ng quốc tế (nếu c&oacute;):</strong></span></span></span></h2>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i c&oacute; thể cung cấp dịch vụ giao h&agrave;ng quốc tế đến một số địa điểm nhất định.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#0d0d0d\">Kh&aacute;ch h&agrave;ng cần lưu &yacute; rằng c&oacute; thể c&oacute; thuế nhập khẩu hoặc c&aacute;c khoản ph&iacute; kh&aacute;c li&ecirc;n quan đến h&agrave;ng h&oacute;a được giao h&agrave;ng quốc tế</span></span></span></p>\r\n\r\n<h2 style=\"margin-left:40px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>8. Ch&iacute;nh s&aacute;ch bảo hiểm vận chuyển:</strong></span></span></span></h2>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">Ch&uacute;ng t&ocirc;i c&oacute; thể cung cấp t&ugrave;y chọn bảo hiểm vận chuyển cho kh&aacute;ch h&agrave;ng với một khoản ph&iacute; bổ sung.</span></span></span></p>\r\n\r\n<p style=\"margin-left:80px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">Bảo hiểm sẽ bảo vệ h&agrave;ng h&oacute;a khỏi c&aacute;c rủi ro trong qu&aacute; tr&igrave;nh vận chuyển.</span></span></span></p>\r\n\r\n<p><img alt=\"Máy lọc nước nano GEYSER ECOLUX A - Made in Russia - Kitchen Store\" src=\"https://kitchenstore.vn/wp-content/uploads/2020/08/Freeship.png\" /></p>\r\n', 'hxqduccopny@gmail.com', 1),
(17, 'The Andaman Islands', '../upload/blogneil-lazy-boy.webp', '2024-04-14 19:13:28', 'The Andaman Islands boasts some of the most pristine and enchanting beaches in the world. Each stretch of sand tells a unique story, inviting travelers to discover a symphony of sun, sea, and serenity. In this blog, we shall embark on a journey to explore the diverse and captivating beaches that make the Andaman Islands a tropical paradise.', 3, '<p dir=\"ltr\">The Andaman Islands boasts some of the most pristine and enchanting beaches in the world. Each stretch of sand tells a unique story, inviting travelers to discover a symphony of sun, sea, and serenity. In this blog, we shall embark on a journey to explore the diverse and captivating beaches that make the Andaman Islands a tropical paradise.</p>\r\n\r\n<h3 dir=\"ltr\">Radhanagar Beach (Havelock Island):</h3>\r\n\r\n<p dir=\"ltr\">Tagged as one of the best beaches in Asia, Radhanagar Beach on Havelock Island is a masterpiece of nature&rsquo;s beauty. The soft, powdery white sands and crystal-clear turquoise waters create a postcard-perfect setting. But it&rsquo;s the sunset on the horizon that will wanna make you hold your breath. The orange glistening sun setting in the background with blue waters, and a wide white sand beach is a sight to behold and truly an experience.</p>\r\n\r\n<h3 dir=\"ltr\">Elephant Beach (Havelock Island):</h3>\r\n\r\n<p dir=\"ltr\">Elephant Beach is named after the elephants that once frequented its shores (not anymore). It&nbsp; is a vibrant destination for water sports enthusiasts. The clear waters give you a spectacular view of a pool of colorful fishes swimming by you. Further, Snorkeling and diving here reveals a kaleidoscope of coral reefs and marine life and you can even spot Nemo. The journey to Elephant Beach is an adventure in itself, often reached by a short trek or a thrilling boat ride, adds to the experience.</p>\r\n\r\n<h3 dir=\"ltr\">Vijaynagar Beach (Havelock Island):</h3>\r\n\r\n<p dir=\"ltr\">Vijaynagar Beach is a hidden gem for those seeking solitude. With its long stretch of untouched shoreline and dense palm groves, it provides a peaceful retreat. The turquoise waters and the gentle rustle of palm leaves create a soothing atmosphere, making it an ideal spot for a swim or a leisurely day under the sun.</p>\r\n\r\n<h3 dir=\"ltr\">Laxmanpur Beach (Neil Island):</h3>\r\n\r\n<p dir=\"ltr\">Laxmanpur Beach on Neil Island is renowned for its stunning coral formations and vibrant marine life. During low tide, the sea retreats, unveiling a natural bridge, also&nbsp; known as the Howrah Bridge. We recommend hiring a local guide who could lead you to this trek and also help spot marine life on the way under the tidal pools. The breathtaking views of the sunset also make it for a romantic setting for couples to click a frame shot for your honeymoon.</p>\r\n\r\n<h3 dir=\"ltr\">Bharatpur Beach (Neil Island):</h3>\r\n\r\n<p dir=\"ltr\">Bharatpur Beach, also on Neil Island, is a lively commercial destination with shallow waters and an abundance of coral reefs. The different hues of water for which the neil island is famous for can be seen here. The beach has a vibrant marine ecosystem which has attracted snorkelers and swimmers alike. It is also famous for water rides like Glass bottom boat rides, banana rides to name a few. The beach is also dotted with local stalls offering coconut water and local delicacies, providing a true taste of island life.</p>\r\n\r\n<h3 dir=\"ltr\">Wandoor Beach (Port Blair):</h3>\r\n\r\n<p dir=\"ltr\">Located near the Mahatma Gandhi Marine National Park, Wandoor Beach is known for its rich biodiversity. The beach serves as the gateway to islands like Jolly Buoy &amp; Red Skin. Swimming here is completely prohibited by the A &amp; N Administration but it&rsquo;s your ideal place if you are traveling with kids. They can play around in the sands making sand castles and you can enjoy a small picnic.</p>\r\n\r\n<h3 dir=\"ltr\">ChidiyaTapu Beach ( Port Blair):</h3>\r\n\r\n<p dir=\"ltr\">As the name suggests, Chidiyatapu is a haven for bird watchers and nature enthusiasts. The beach is embraced by dense mangrove forests with reserve forests. As the sun begins its descent, Chidiyatapu transforms into a mesmerizing spot for sunset lovers. The sky blushes with hues of orange and pink, casting a warm glow over the tranquil waters. The beach is a serene escape from the bustling city life of Port Blair for locals and&nbsp; provides a peaceful setting to unwind and connect with nature.</p>\r\n\r\n<p dir=\"ltr\">Apart from its scenic beauty, Chidiyatapu is also a gateway to the MundaPahar Trek and the Chidiya Tapu Biological Park, making it a comprehensive destination for those seeking a blend of beachside relaxation and exploration of the rich biodiversity that the Andaman Islands have to offer. Whether you are drawn to the melodious chirping of birds, the soothing sound of waves, or the breathtaking sunset vistas, Chidiyatapu Beach invites you to experience the untouched allure right outside the city.</p>\r\n\r\n<h3 dir=\"ltr\">Corbyn&rsquo;s Cove Beach (Port Blair):</h3>\r\n\r\n<p dir=\"ltr\">A picturesque &amp; short drive from Port Blair City, Corbyn&rsquo;s Cove Beach is a popular spot for both locals and tourists. The coconut palm-fringed shoreline and the gentle waves make it an inviting place for a leisurely swim. The beach also offers water sports like Jet Ski, Parasailing, making it an excellent choice for those seeking a bit of adventure at Port Blair.</p>\r\n\r\n<h3 dir=\"ltr\">Kala Pathar Beach (Havelock Island):</h3>\r\n\r\n<p dir=\"ltr\">Named after the distinctive black rocks that line its shores, Kala Pathar Beach is a secluded haven on Havelock Island. The contrast of the ebony rocks against the golden sands and turquoise waters creates a dramatic landscape. The beach is not a great place to swim but perfect for those looking to escape the crowds and immerse themselves in nature&rsquo;s beauty.</p>\r\n\r\n<h3 dir=\"ltr\">Conclusion:</h3>\r\n\r\n<p dir=\"ltr\">The Andaman Islands are a treasure trove of beaches, each with its own unique charm. From the idyllic shores of Radhanagar Beach to the adventurous waters of Elephant Beach, and the serene beauty of Laxmanpur Beach to the vibrant marine life at Bharatpur Beach, these sandy stretches offer an array of experiences for every traveler. Whether you seek relaxation, adventure, or a glimpse into the underwater world, the beaches of the Andaman Islands beckon, promising an unforgettable escape into paradise.</p>\r\n', 'hxqduccopny@gmail.com', 0),
(18, 'Cách Làm Đồ Trang Trí Bằng Vỏ Ngao Siêu Xinh', '../upload/blogcach-lam-do-trang-tri-bang-vo-ngao-khung-tranh.jpg', '2024-04-14 19:16:58', 'Có rất nhiều cách làm đồ trang trí bằng vỏ ngao để tô điểm cho ngôi nhà của bạn. Dưới đây là một số gợi ý, bạn có thể tham khảo để tạo ra cho mình các món đồ handmade xinh xắn nhé.', 2, '<h2>Chuẩn bị vỏ ngao để l&agrave;m đồ trang tr&iacute;</h2>\r\n\r\n<p>Để c&oacute; những m&oacute;n đồ trang tr&iacute; xinh xắn th&igrave; bước chuẩn bị vỏ ngao rất quan trọng. Bạn n&ecirc;n chọn những vỏ c&ograve;n nguy&ecirc;n vẹn, c&oacute; k&iacute;ch thước ph&ugrave; hợp với nhu cầu sử dụng. Sau bước rửa sạch vỏ ngao, bạn lau kh&ocirc; để khi d&aacute;n keo sẽ chắc chắn hơn.&nbsp;</p>\r\n\r\n<p>B&ecirc;n cạnh đ&oacute;, bạn n&ecirc;n chuẩn bị th&ecirc;m b&uacute;t l&ocirc;ng hoặc m&agrave;u vẽ. Sơn nhiều m&agrave;u sắc l&ecirc;n vỏ ngao sẽ gi&uacute;p c&aacute;c m&oacute;n đồ trang tr&iacute; th&ecirc;m sinh động, rực rỡ hơn.</p>\r\n\r\n<p><img alt=\"Vỏ ngao là nguyên liệu làm đồ handmade được rất nhiều người yêu thích\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-dep.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-dep.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-dep-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-dep-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Vỏ ngao l&agrave; nguy&ecirc;n liệu l&agrave;m đồ handmade được rất nhiều người y&ecirc;u th&iacute;ch</p>\r\n\r\n<p><img alt=\"Bạn có thể mua vỏ ngao trắng hoặc loại đã nhuộm màu để tạo ra các món đồ trang trí rực rỡ hơn\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-xinh-xan.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-xinh-xan.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-xinh-xan-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-xinh-xan-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Bạn c&oacute; thể mua vỏ ngao trắng hoặc loại đ&atilde; nhuộm m&agrave;u để tạo ra c&aacute;c m&oacute;n đồ trang tr&iacute; rực rỡ hơn</p>\r\n\r\n<h2>Thiết kế gương với vỏ ngao</h2>\r\n\r\n<p>C&aacute;ch l&agrave;m đồ trang tr&iacute; bằng vỏ ngao với gương kh&aacute; đơn giản. Đầu ti&ecirc;n bạn cần c&oacute; một chiếc gương để b&agrave;n hoặc gương treo tường. Bất cứ k&iacute;ch cỡ gương n&agrave;o cũng c&oacute; thể l&agrave;m được. Nhưng với gương lớn th&igrave; cần lượng vỏ ngao rất nhiều n&ecirc;n bạn h&atilde;y c&acirc;n nhắc nh&eacute;.&nbsp;</p>\r\n\r\n<p>Bạn d&ugrave;ng keo nến để d&aacute;n vỏ ngao l&ecirc;n xung quanh th&agrave;nh gương. Ngo&agrave;i ra, bạn cũng c&oacute; thể gắn th&ecirc;m vỏ ốc, sỏi nhỏ, sao biển để chiếc gương th&ecirc;m xinh xắn.</p>\r\n\r\n<p><img alt=\"Cách làm đồ trang trí bằng vỏ ngao với gương soi khá đơn giản mà thành phẩm thì cực kỳ xinh\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>C&aacute;ch l&agrave;m đồ trang tr&iacute; bằng vỏ ngao với gương soi kh&aacute; đơn giản m&agrave; th&agrave;nh phẩm th&igrave; cực kỳ xinh</p>\r\n\r\n<p><img alt=\"Bạn cũng có thể tham khảo cách kết hợp thêm các loại vỏ ốc, san hô nhiều màu sắc\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-soi.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-soi.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-soi-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-soi-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Bạn cũng c&oacute; thể tham khảo c&aacute;ch kết hợp th&ecirc;m c&aacute;c loại vỏ ốc, san h&ocirc; nhiều m&agrave;u sắc</p>\r\n\r\n<p><img alt=\"Bạn tha hồ sáng tạo với vô vàn cách sắp xếp các loại vỏ ngao, vỏ ốc để tạo nên chiếc gương trang trí không đụng hàng\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-treo-tuong.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-treo-tuong.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-treo-tuong-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-treo-tuong-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Bạn tha hồ s&aacute;ng tạo với v&ocirc; v&agrave;n c&aacute;ch sắp xếp c&aacute;c loại vỏ ngao, vỏ ốc để tạo n&ecirc;n chiếc gương trang tr&iacute; kh&ocirc;ng đụng h&agrave;ng</p>\r\n\r\n<p><img alt=\"Không gian sống trở nên lãng mạn, ngọt ngào hơn khi bạn dành thời gian sáng tạo làm đồ trang trí bằng vỏ ngao\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-vuong.jpeg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-vuong.jpeg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-vuong-300x200.jpeg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-va-guong-vuong-768x512.jpeg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Kh&ocirc;ng gian sống trở n&ecirc;n l&atilde;ng mạn, ngọt ng&agrave;o hơn khi bạn d&agrave;nh thời gian s&aacute;ng tạo l&agrave;m đồ trang tr&iacute; bằng vỏ ngao</p>\r\n\r\n<h2>C&aacute;ch l&agrave;m đồ trang tr&iacute; bằng vỏ ngao &ndash; Khung ảnh vỏ ngao</h2>\r\n\r\n<p>Tương tự như với gương, bạn cũng c&oacute; thể mua c&aacute;c loại khung ảnh đơn giản về rồi d&ugrave;ng vỏ ngao trang tr&iacute;. Bạn n&ecirc;n lưu &yacute; chọn k&iacute;ch thước vỏ ngao ph&ugrave; hợp với khung ảnh, khung lớn th&igrave; d&ugrave;ng vỏ lớn, khung nhỏ d&ugrave;ng vỏ nhỏ.&nbsp;</p>\r\n\r\n<p>Bạn c&oacute; thể sơn m&agrave;u l&ecirc;n vỏ ngao để khung ảnh xinh hơn, ph&ugrave; hợp với những bức h&igrave;nh m&agrave; bạn sẽ lồng v&agrave;o khung.</p>\r\n\r\n<p><img alt=\"Ảnh chụp ở biển lồng khung vỏ ngao thì quá hợp phải không nào?\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-de-ban.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-de-ban.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-de-ban-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-de-ban-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Ảnh chụp ở biển lồng khung vỏ ngao th&igrave; qu&aacute; hợp phải kh&ocirc;ng n&agrave;o?</p>\r\n\r\n<p><img alt=\"Nếu thích sự đơn giản thì bạn có thể tham khảo cách trang trí này\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Nếu th&iacute;ch sự đơn giản th&igrave; bạn c&oacute; thể tham khảo c&aacute;ch trang tr&iacute; n&agrave;y</p>\r\n\r\n<p><img alt=\"Hoặc cách làm đồ trang trí bằng vỏ ngao với mẫu khung ảnh này cũng rất đơn giản, dễ làm\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-nho.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-nho.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-nho-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-nho-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Hoặc c&aacute;ch l&agrave;m đồ trang tr&iacute; bằng vỏ ngao với mẫu khung ảnh n&agrave;y cũng rất đơn giản, dễ l&agrave;m</p>\r\n\r\n<p><img alt=\"Chỉ với vài chiếc vỏ ngao, vỏ ốc bạn đã có một chiếc khung ảnh handmade cực xinh\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-dep.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-dep.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-dep-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-danh-cho-khung-anh-dep-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Chỉ với v&agrave;i chiếc vỏ ngao, vỏ ốc bạn đ&atilde; c&oacute; một chiếc khung ảnh handmade cực xinh</p>\r\n\r\n<h2>Thiết kế c&acirc;y trang tr&iacute; bằng vỏ ngao</h2>\r\n\r\n<p>C&aacute;ch l&agrave;m c&acirc;y trang tr&iacute; bằng vỏ ngao sẽ phức tạp hơn một ch&uacute;t. Đầu ti&ecirc;n, bạn cần phần th&acirc;n c&acirc;y bằng khung sắt, th&eacute;p chắc chắn. Sau đ&oacute;, bạn sẽ d&ugrave;ng vỏ ngao tạo h&igrave;nh l&aacute;, hoa gắn quanh th&acirc;n. Để c&acirc;y đẹp mắt hơn, bạn th&ecirc;m những chiếc vỏ ốc, kim tuyến&hellip;</p>\r\n\r\n<p>Ngo&agrave;i ra, bạn cũng c&oacute; thể tận dụng c&aacute;c c&acirc;y cảnh giả đ&atilde; cũ để trang tr&iacute; lại. C&aacute;ch l&agrave;m đồ trang tr&iacute; bằng vỏ ngao n&agrave;y cần ch&uacute; trọng gắn thật chắc, tr&aacute;nh để vỏ rơi rụng khi trưng b&agrave;y.</p>\r\n\r\n<p><img alt=\"Với các loại vỏ ngao, vỏ ốc gắn quanh trục là bạn đã có một cây thông xinh xắn\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-xinh-xan.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-xinh-xan.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-xinh-xan-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-xinh-xan-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Với c&aacute;c loại vỏ ngao, vỏ ốc gắn quanh trục l&agrave; bạn đ&atilde; c&oacute; một c&acirc;y th&ocirc;ng xinh xắn</p>\r\n\r\n<p><img alt=\"Còn nếu chỉ dùng vỏ ngao thì thành phẩm sẽ như thế này\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>C&ograve;n nếu chỉ d&ugrave;ng vỏ ngao th&igrave; th&agrave;nh phẩm sẽ như thế n&agrave;y</p>\r\n\r\n<p><img alt=\"Điểm xuyết thêm ngọc trai nhiều màu sẽ giúp cây bắt mắt hơn\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-de-ban.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-de-ban.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-de-ban-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-de-ban-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Điểm xuyết th&ecirc;m ngọc trai nhiều m&agrave;u sẽ gi&uacute;p c&acirc;y bắt mắt hơn</p>\r\n\r\n<p><img alt=\"Gắn thật chặt vỏ ngao để cây của bạn trang bày được lâu dài, không bị rơi rụng, hư hỏng nhé\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-dep.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-dep.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-dep-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tao-hinh-cay-dep-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Gắn thật chặt vỏ ngao để c&acirc;y của bạn trang b&agrave;y được l&acirc;u d&agrave;i, kh&ocirc;ng bị rơi rụng, hư hỏng nh&eacute;</p>\r\n\r\n<h2>Trang tr&iacute; bức tranh</h2>\r\n\r\n<p>S&aacute;ng tạo l&agrave;m đồ trang tr&iacute; bằng vỏ ngao tạo th&agrave;nh tranh treo tường được rất nhiều người y&ecirc;u th&iacute;ch. Bạn c&oacute; thể d&ugrave;ng vỏ ngao tạo h&igrave;nh b&ocirc;ng hoa, ngọn n&uacute;i, b&atilde;i biển&hellip; tr&ecirc;n giấy A4.</p>\r\n\r\n<p>Sau đ&oacute; d&ugrave;ng b&uacute;t m&agrave;u t&ocirc; điểm th&ecirc;m l&agrave; lồng khung l&agrave; đ&atilde; c&oacute; một bức tranh rất xinh xắn rồi.</p>\r\n\r\n<p><img alt=\"Nếu khéo tay hơn thì bạn có thể thử cách tạo hình khá phức tạp này\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-khung-tranh.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-khung-tranh.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-khung-tranh-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-khung-tranh-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Nếu kh&eacute;o tay hơn th&igrave; bạn c&oacute; thể thử c&aacute;ch tạo h&igrave;nh kh&aacute; phức tạp n&agrave;y</p>\r\n\r\n<p><img alt=\"Các bức tranh nghệ thuật bằng vỏ ngao, vỏ sò cực kỳ đầu tư\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-khung-tranh-dep.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-khung-tranh-dep.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-khung-tranh-dep-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-khung-tranh-dep-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>C&aacute;c bức tranh nghệ thuật bằng vỏ ngao, vỏ s&ograve; cực kỳ đầu tư</p>\r\n\r\n<p><img alt=\"Bạn có thể tham khảo các mẫu đơn giản hơn như thế này để dễ thực hiện\" data-ll-status=\"loaded\" loading=\"lazy\" onload=\"pagespeed.CriticalImages.checkImageForCriticality(this);\" pagespeed_url_hash=\"326922916\" sizes=\"(max-width: 900px) 100vw, 900px\" src=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tranh-treo-tuong.jpg\" srcset=\"https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tranh-treo-tuong.jpg 900w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tranh-treo-tuong-300x200.jpg 300w, https://www.btaskee.com/wp-content/uploads/2023/04/cach-lam-do-trang-tri-bang-vo-ngao-tranh-treo-tuong-768x512.jpg 768w\" style=\"height:600px; width:900px\" /></p>\r\n\r\n<p>Bạn c&oacute; thể tham khảo c&aacute;c mẫu đơn giản hơn như thế n&agrave;y để dễ thực hiện</p>\r\n', 'hxqduccopny@gmail.com', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categoryproduct`
--

CREATE TABLE `categoryproduct` (
  `categoryproduct_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `category_link` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categoryproduct`
--

INSERT INTO `categoryproduct` (`categoryproduct_id`, `name`, `category_link`, `code`, `image`, `created_by`, `description`) VALUES
(16, 'Nút áo RiverShell', 'nut-ao-rivershell', 'CT001', '../upload/category/nut-ao-rivershell/ốc trắng to.jpg', 'admin', 'CT001'),
(18, 'Nút áo Trocas', 'nut-ao-trocas', 'CT003', '../upload/category/nut-ao-trocas/ốc đen dày.JPG', 'admin', 'CT003'),
(19, 'Nút áo từ vỏ sò điệp', 'nut-ao-tu-vo-so-diep', 'CT004', '../upload/category/nut-ao-tu-vo-so-diep/sò điệp.jpg', 'admin', 'CT004'),
(20, 'Vỏ ốc', 'vo-oc', 'CT005', '../upload/category/vo-oc/vỏ ốc 1.JPG', 'admin', 'CT005'),
(26, 'Nút áo MOP', 'nut-ao-mop', 'CT002', '../upload/category/nut-ao-tamagai/ốc vàng.jpg', 'admin', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category_blog`
--

CREATE TABLE `category_blog` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category_blog`
--

INSERT INTO `category_blog` (`id`, `name`, `created_at`) VALUES
(1, 'Tin tức mới nhất', '2024-04-08 09:20:03'),
(2, 'Trang trí nhà cửa', '2024-04-08 09:20:03'),
(3, 'Vẻ đẹp cuộc sống', '2024-04-08 09:20:57'),
(4, 'Sản xuất và giá cả', '2024-04-08 09:20:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category_expense`
--

CREATE TABLE `category_expense` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category_expense`
--

INSERT INTO `category_expense` (`id`, `name`, `code`, `status`, `amount`, `description`, `created_at`) VALUES
(1, 'mua nguyen lieu', 'EX001', 'Active', 12000.00, 'aaa', '2023-09-05 17:03:19'),
(3, 'chi phi cong trinh', 'EX003', 'In Active', 13000.00, 'sdsd', '2023-09-05 17:08:47'),
(4, 'nguyen lieu 2', 'EX002', 'Active', 13000.00, '23444', '2023-09-05 19:32:01'),
(5, 'chi phi cong trinh', 'EX005', 'Active', 12000.00, '', '2024-04-13 14:39:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category_sub`
--

CREATE TABLE `category_sub` (
  `category_sub_id` int(11) NOT NULL,
  `categoryproduct_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category_link` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category_sub`
--

INSERT INTO `category_sub` (`category_sub_id`, `categoryproduct_id`, `name`, `code`, `description`, `category_link`, `created_by`) VALUES
(7, 17, 'camdfr', 'FHT', '', 'camdfr', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `blog_id` int(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comment`
--

INSERT INTO `comment` (`id`, `email`, `content`, `blog_id`, `created_at`, `name`) VALUES
(6, '2156210100@gmail.com', 'Bài viết còn thiếu', 4, '2024-04-11 05:11:05', 'Hoàng Xuân Quốc'),
(7, '2156210100@gmail.com', 'Viết thêm một số cái nữa', 4, '2024-04-11 05:11:28', 'Davis hoàng'),
(8, '2156210100@gmail.com', 'cần cải thiện hơn nữa', 3, '2024-04-11 05:14:59', 'Hoàng Xuân Quốc'),
(9, '2156210100@gmail.com', 'Quá nhiều thép\n', 3, '2024-04-11 06:20:35', 'Hoàng Xuân Quốc'),
(10, '2156210100@gmail.com', 'Cần phát triền thêm bê rông', 3, '2024-04-11 06:20:50', 'Hoàng Xuân Quốc'),
(11, '2156210100@gmail.com', 'Nâng thêm phần đâu', 3, '2024-04-11 06:21:08', 'Hoàng Xuân Quốc'),
(12, '2156210100@gmail.com', 'Quá nhiều người', 3, '2024-04-11 06:22:30', 'Hoàng Xuân Quốc'),
(13, '2156210100@gmail.com', 'Insert to comment', 3, '2024-04-11 06:37:21', 'Hoàng Xuân Quốc'),
(14, '2156210100@gmail.com', 'cần cải thiện hơn nữa1', 3, '2024-04-11 06:37:49', 'Hoàng Xuân Quốc'),
(15, '2156210100@gmail.com', 'cần cải thiện hơn nữa2', 3, '2024-04-11 06:37:52', 'Hoàng Xuân Quốc'),
(16, '2156210100@gmail.com', 'cần cải thiện hơn nữa3', 3, '2024-04-11 06:37:56', 'Hoàng Xuân Quốc'),
(17, '2156210100@gmail.com', '1112', 3, '2024-04-11 06:39:15', 'Hoàng Xuân Quốc'),
(18, '2156210100@gmail.com', '113', 3, '2024-04-11 06:39:21', 'Hoàng Xuân Quốc'),
(19, '2156210100@gmail.com', '113', 3, '2024-04-11 06:40:59', 'Hoàng Xuân Quốc'),
(20, '2156210100@gmail.com', '114', 3, '2024-04-11 06:41:05', 'Hoàng Xuân Quốc'),
(21, '2156210100@gmail.com', '115', 3, '2024-04-11 06:41:26', 'Hoàng Xuân Quốc'),
(22, '2156210100@gmail.com', '116', 3, '2024-04-11 06:41:31', 'Hoàng Xuân Quốc'),
(23, '2156210100@gmail.com', '12323', 3, '2024-04-11 06:41:55', 'Hoàng Xuân Quốc'),
(24, '2156210100@gmail.com', '12323', 3, '2024-04-11 06:42:00', 'Hoàng Xuân Quốc'),
(25, '2156210100@gmail.com', '232323', 3, '2024-04-11 06:42:12', 'Hoàng Xuân Quốc'),
(26, '2156210100@gmail.com', 'dfdfd', 3, '2024-04-12 19:04:16', 'Hoàng Xuân Quốc'),
(27, '215621086@gmail.com', 'hello there', 4, '2024-04-13 08:43:56', 'Nguyen Thanh Hoang'),
(28, '2156210100@gmail.com', 'phát triển thêm nữa hen', 4, '2024-04-13 17:56:58', 'Hoàng Xuân Quốc'),
(30, '2156210100@gmail.com', 'thành công ha', 4, '2024-04-13 18:01:40', 'Hoàng Xuân Quốc'),
(34, '2156210100@gmail.com', 'chaiyo', 4, '2024-04-13 18:11:45', 'Hoàng Xuân Quốc'),
(35, '2156210100@gmail.com', 'sắp xong gòi', 4, '2024-04-13 18:14:06', 'Hoàng Xuân Quốc'),
(36, '2156210100@gmail.com', 'mở đầu trang ', 18, '2024-04-14 20:24:55', 'Hoàng Xuân Quốc');

--
-- Bẫy `comment`
--
DELIMITER $$
CREATE TRIGGER `after_insert_comment_notification` AFTER INSERT ON `comment` FOR EACH ROW BEGIN
    DECLARE userEmail VARCHAR(255);
    DECLARE blogTitle VARCHAR(255);
    DECLARE bloginId INT;

    -- Lấy email từ bảng comment
    SELECT email INTO userEmail FROM comment WHERE id = NEW.id;

    -- Lấy blog_id từ bảng comment
 
    SELECT blog_id INTO bloginId FROM comment WHERE id = NEW.id;

    -- Lấy title của blog từ bảng blog
    SELECT title INTO blogTitle FROM blog WHERE id = bloginId;

    -- Chèn thông báo vào bảng notifications
    INSERT INTO notification (email, message)
    VALUES (userEmail, CONCAT('added comment for ', blogTitle));
    DELETE FROM notification
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customer`
--

INSERT INTO `customer` (`id`, `name`, `phone`, `image`, `email`, `type`, `country`, `city`, `district`, `address`, `zipcode`, `description`, `code`, `created_at`, `updated_at`) VALUES
(19, 'Hoàng Xuân Quốc', '84865625135', '../upload/customer/avatar-03.jpg', '2156210125@gmail.com', 'Customer normal', 'Vietnam', 'Đồng Tháp', 'Cao Lãnh', 'Sau phòng giáo dục gio linh, khu phố 2, thị trấn gio linh', '1233232', '', 'CTM001', NULL, '2023-08-25 03:13:20'),
(20, 'Hoàng Xuân Quốca2', '84865625135', '../upload/customer/avatar-17.jpg', '21562101253@gmail.com', 'Customer normal', 'Vietnam', 'Bà Rịa-Vũng Tàu', 'Huyện Đất Đỏ', 'Sau phòng giáo dục gio linh, khu phố 2, thị trấn gio linh', '76767', '34343', 'CTM002', NULL, '2023-08-25 03:19:22'),
(21, 'Nguyễn Nhân', '0547288132', '../upload/customer/avatar-13.jpg', 'hoangnhan@gmail.com', 'Customer Pro', 'France', 'Nouvelle-Aquitaine', 'Agonac', 'france dejong la', '123', 'dsdsds', 'CTM003', NULL, '2023-08-28 18:02:46'),
(35, 'Nguyenhanhoang', '84865625135', '../upload/customer/profile4.jpg', '215621045445@gmail.com', 'Choose Type', 'Vietnam', 'Select State', 'Choose District', 'KTX KHU B', '23232', '23232', 'CTM004', NULL, '2023-09-02 14:23:28'),
(36, 'Nguyen hoang', '0358423356', '../upload/customer/avt-tvs-1641547808814523708345.jpg', '1907htng@gmail.com', 'Customer normal', 'Vietnam', 'Select State', 'Select City', '343, cmt8', '125423', 'sedrwewe', 'CTM005', NULL, '2023-09-06 01:48:23'),
(37, 'Aris', '0874423356', '../upload/customer/', '2156210177@gmail.com', 'Choose Type', 'Select Country', 'Choose state/city', 'Choose District', '', '', '', 'CTM006', NULL, '2024-04-06 05:29:13'),
(38, 'hanhanthanh', '', '../upload/customer/', 'hoangnhangmj@gmail.com', 'Choose Type', 'Select Country', 'Choose state/city', 'Choose District', '', '', '', 'CTM007', NULL, '2024-04-06 05:40:39'),
(41, 'Hoàng Xuân Thành', '', '../upload/customer/', '2156210166@gmail.com', '', '', '', '', '', '', '', 'CTM008', NULL, '2024-04-06 08:29:52'),
(42, 'Hoàng Xuân Thành nhân', '', '../upload/customer/', '2156210167@gmail.com', '', '', '', '', '', '', '', 'CTM009', NULL, '2024-04-06 08:32:17'),
(43, 'Hoàng Xuân Quốc', '4545454', '../upload/customer/', '2156210100@gmail.com', '', 'Australia', 'Queensland', 'Annandale', 'Sau phòng giáo dục gio linh, khu phố 2, thị trấn gio linh', '820000', '', 'CTM010', NULL, '2024-04-08 08:31:45'),
(44, 'Nguyen Thanh Hoang', '', '../upload/customer/', '215621086@gmail.com', '', 'Algeria', 'Jijel', 'Jijel', '', '', '', 'CTM011', NULL, '2024-04-13 08:31:54');

--
-- Bẫy `customer`
--
DELIMITER $$
CREATE TRIGGER `trg_before_insert_customer` BEFORE INSERT ON `customer` FOR EACH ROW BEGIN
    DECLARE new_code VARCHAR(10);
    SET new_code = CONCAT('CTM', LPAD((SELECT COUNT(*) + 1 FROM customer), 3, '0'));
    SET NEW.code = new_code;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `discount`
--

CREATE TABLE `discount` (
  `discount_id` bigint(20) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `content` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(30) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `minium_value` decimal(10,2) DEFAULT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `discount`
--

INSERT INTO `discount` (`discount_id`, `discount_amount`, `start_date`, `end_date`, `content`, `status`, `created_at`, `updated_at`, `type`, `quantity`, `minium_value`, `max_discount`, `reference`) VALUES
(3, 0.60, '2023-08-19 00:00:00', '2023-08-31 00:00:00', 'Discount t92', 0, NULL, '2023-09-02 18:18:42', 'customer', -104.00, 1000.00, 100000.00, 'GG001'),
(4, 0.20, '2023-08-19 00:00:00', '2023-08-31 00:00:00', 'Discount t82', 0, NULL, '2023-09-02 18:18:42', 'customer', -228.00, 100.00, 100000.00, 'GG003'),
(6, 0.20, '2023-08-19 00:00:00', '2023-09-21 00:00:00', 'Discount t83', 0, NULL, '2024-04-06 14:53:08', 'customer', 68.00, 100.00, 100000.00, 'GG004');

--
-- Bẫy `discount`
--
DELIMITER $$
CREATE TRIGGER `before_discount_update_status` BEFORE UPDATE ON `discount` FOR EACH ROW BEGIN
    IF NEW.start_date IS NOT NULL AND NEW.end_date IS NOT NULL THEN
        IF CURDATE() < NEW.start_date OR CURDATE() > NEW.end_date THEN
            SET NEW.status = 0;
        END IF;
    END IF;

    IF NEW.quantity <= 0 THEN
        SET NEW.status = 0;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_discount_status` BEFORE INSERT ON `discount` FOR EACH ROW BEGIN
    IF NEW.start_date IS NOT NULL AND NEW.end_date IS NOT NULL THEN
        IF CURDATE() < NEW.start_date OR CURDATE() > NEW.end_date THEN
            SET NEW.status = 0;
        END IF;
    END IF;

    IF NEW.quantity <= 0 THEN
        SET NEW.status = 0;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `discount_reference_beforeinsert` BEFORE INSERT ON `discount` FOR EACH ROW BEGIN
    DECLARE next_id INT;
    
    -- Lấy ra số thứ tự tiếp theo
    SELECT IFNULL(MAX(CAST(SUBSTRING_INDEX(reference, 'GG', -1) AS UNSIGNED)), 0) + 1 INTO next_id FROM discount;
    
    -- Nếu không tìm thấy hàng nào hoặc trường reference là NULL, giá trị tiếp theo bắt đầu từ SLL001
    IF next_id = 0 OR NEW.reference IS NULL THEN
        SET NEW.reference = 'GG001';
    ELSE
        -- Tạo giá trị mới cho reference
        SET NEW.reference = CONCAT('GG', LPAD(next_id, 3, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_setting`
--

CREATE TABLE `email_setting` (
  `id` int(11) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` varchar(255) NOT NULL,
  `address_server` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `subject_forgot` text DEFAULT NULL,
  `subject_advertse` text DEFAULT NULL,
  `content_forgot` text DEFAULT NULL,
  `content_advertse` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `email_setting`
--

INSERT INTO `email_setting` (`id`, `host`, `port`, `address_server`, `password`, `business_name`, `subject_forgot`, `subject_advertse`, `content_forgot`, `content_advertse`) VALUES
(1, 'smtp.gmail.com', '587', 'cskhhoanghaigroup@gmail.com', 'wxbazgsjxcxlcjgn', 'First Last', 'PHPMailer GMail SMTP test', 'PHPMailer GMail SMTP test', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"u_content_text_2\" style=\"font-family:\'Rubik\',sans-serif; width:100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<div class=\"v-font-size v-text-align\" style=\"color:#5c5c5c; font-size:14px; line-height:170%; text-align:left; word-wrap:break-word\">\r\n			<p><span style=\"font-size:16px\"><strong>Hi [Candidate&rsquo;s Name], </strong></span></p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><span style=\"font-size:16px\">Received your mail and hope this mail reads you well. Talent acquisition often requires a huge investment of time and resources, so it is always safe and secure to do your due diligence before onboarding a new hire.&nbsp;</span></p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><span style=\"font-size:16px\">To go ahead with your data verification, we would require these documents from you:</span></p>\r\n\r\n			<p><strong><span style=\"font-size:16px\">[document names]</span></strong></p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><span style=\"font-size:16px\">This will ensure the authenticity of your data and information with the verification process is pretty simple and short. The information you provide is confidential and safe with us.&nbsp;</span></p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><span style=\"font-size:16px\">If you have any questions/issues regarding the process, feel free to contact us.&nbsp;</span></p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><span style=\"font-size:16px\">With Regards,</span></p>\r\n\r\n			<p><span style=\"font-size:16px\"><strong>[Your name]</strong></span></p>\r\n			</div>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n', '<p>PHPMailer G1232</p>\r\n');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `expense`
--

CREATE TABLE `expense` (
  `id` bigint(20) NOT NULL,
  `categoryex_id` int(20) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `expense_for` text DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `expense`
--

INSERT INTO `expense` (`id`, `categoryex_id`, `code`, `expense_for`, `status`, `amount`, `description`, `created_at`) VALUES
(8, 4, 'EXE001', 'category2', 'Active', 13000.00, 'sdss', '2023-09-04 17:00:00'),
(10, 1, 'EXE002', 'category', 'In Active', 12000.00, '666', '2023-09-06 17:00:00'),
(12, 1, 'EXE004', 'category', 'Active', 12000.00, '', '2023-09-05 17:00:00'),
(14, 5, 'EXE005', 'category', 'In Active', 12000.00, '', '2024-04-12 17:00:00'),
(15, 1, 'EXE006', 'category', 'Active', 1200.00, '', '2024-03-12 17:00:00');

--
-- Bẫy `expense`
--
DELIMITER $$
CREATE TRIGGER `before_insert_code__expense` BEFORE INSERT ON `expense` FOR EACH ROW BEGIN
    DECLARE next_id INT;

    -- Lấy phần số thứ tự tiếp theo từ chuỗi code
    SELECT IFNULL(MAX(CAST(SUBSTRING_INDEX(code, 'EXE', -1) AS UNSIGNED)), 0) INTO next_id FROM expense;

    -- Tính toán giá trị tiếp theo
    SET next_id = next_id + 1;

    -- Đảm bảo next_id không vượt quá giá trị tối đa (ví dụ: 999)
    IF next_id > 10000000 THEN
        SET next_id = 10000000;
    END IF;

    -- Tạo giá trị mới cho code với định dạng 'EXExxx'
    SET NEW.code = CONCAT('EXE', LPAD(next_id, 3, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `general_setting`
--

CREATE TABLE `general_setting` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `time_zone` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_format` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `fax` text DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `general_setting`
--

INSERT INTO `general_setting` (`id`, `title`, `time_zone`, `image`, `date`, `date_format`, `phone`, `email`, `address`, `fax`, `currency`, `facebook`, `youtube`, `linkedin`, `instagram`) VALUES
(1, 'Samry Shell', 'GMT+7', '../upload/general/661c366dbe8b1_logo-black-1.png', '2024-04-05 10:20:22', 'DD/MM/YYYY', '0865625135', '2156210125@hcmussh.edu.vn', 'Phường 1', '0865625135', 'VND', 'https://www.facebook.com/profile.php?id=100066425750609', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `image_product`
--

CREATE TABLE `image_product` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `image_product`
--

INSERT INTO `image_product` (`id`, `image`, `product_id`) VALUES
(121, '../upload/661c023fba590.jpg', 107),
(122, '../upload/661c023fbaadf.jpg', 107),
(123, '../upload/661c0562abe9d.JPG', 108),
(124, '../upload/661c0562ac99c.jpg', 108),
(125, '../upload/661c078f14bee.jpg', 109),
(126, '../upload/661c078f15081.jpg', 109),
(127, '../upload/661c08b45986e.JPG', 110),
(128, '../upload/661c08b45a15e.jpg', 110),
(129, '../upload/661c09c25f553.jpg', 111),
(130, '../upload/661c09c25face.jpg', 111),
(131, '../upload/661c0a95ee1c1.JPG', 112),
(132, '../upload/661c0a95ee676.jpg', 112),
(133, '../upload/661c0b1be377c.jpg', 103),
(134, '../upload/661c0b1be3dba.jpg', 103),
(135, '../upload/661c0b954dae1.jpg', 104),
(136, '../upload/661c0b954e09f.jpg', 104),
(137, '../upload/661c0c41c45ae.jpg', 105),
(138, '../upload/661c0c41c4db9.jpg', 105),
(139, '../upload/661c0d51e82e4.jpg', 106),
(140, '../upload/661c0d51e8784.jpg', 106);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `notification`
--

INSERT INTO `notification` (`id`, `email`, `message`, `created_at`) VALUES
(7, '2156210100@gmail.com', 'added comment for Phong cách Industrial – 6 bước trang trí nhà đẹp kiểu công nghiệp', '2024-04-13 18:11:45'),
(8, '2156210100@gmail.com', 'added comment for Phong cách Industrial – 6 bước trang trí nhà đẹp kiểu công nghiệp', '2024-04-13 18:14:06'),
(9, '2156210100@gmail.com', 'New order created', '2024-04-13 18:14:30'),
(10, '2156210100@gmail.com', 'New order created', '2024-04-13 18:50:46'),
(11, '2156210100@gmail.com', 'New order created', '2024-04-14 19:52:11'),
(12, '2156210100@gmail.com', 'added comment for Cách Làm Đồ Trang Trí Bằng Vỏ Ngao Siêu Xinh', '2024-04-14 20:24:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_detail`
--

CREATE TABLE `payment_detail` (
  `id` bigint(20) NOT NULL,
  `payment_name` varchar(20) NOT NULL,
  `sale_id` bigint(20) NOT NULL,
  `valueplus` decimal(10,2) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `note` text DEFAULT NULL,
  `paymentconst` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payment_detail`
--

INSERT INTO `payment_detail` (`id`, `payment_name`, `sale_id`, `valueplus`, `status`, `created_at`, `updated_at`, `note`, `paymentconst`) VALUES
(72, 'Cash', 125, 1000.00, 'pending', '2023-09-03 01:48:56', '2023-09-02 18:48:56', '', 'salepayment'),
(73, 'Cash', 139, 180.00, 'success', '2024-04-06 08:44:10', '2024-04-06 01:44:10', '', 'salepayment'),
(74, 'Cash', 136, 2030.00, 'success', '2024-04-09 00:32:01', '2024-04-08 17:32:01', '', 'salepayment');

--
-- Bẫy `payment_detail`
--
DELIMITER $$
CREATE TRIGGER `after_insert_paid_due` AFTER INSERT ON `payment_detail` FOR EACH ROW BEGIN
    DECLARE total_paid DECIMAL(10, 2);
    DECLARE total_due DECIMAL(10, 2);
    DECLARE payment_status VARCHAR(255);

    -- Get current Paid and Due values for the sale
    SELECT Paid, Due,payment INTO total_paid, total_due,payment_status
    FROM sale
    WHERE sale_id = NEW.sale_id;
	IF payment_status != 'Paid' THEN
    
    -- Update Paid and Due with the new values
    SET total_paid = total_paid + NEW.valueplus;
    SET total_due = total_due - NEW.valueplus;
    
    -- Make sure valueplus is positive and status is 'access'
     IF NEW.valueplus > 0 AND NEW.status = 'success' THEN
        -- Check if total_due is negative and reset it to 0
        IF total_due <= 0 THEN
      
            SET total_due = 0;
            IF payment_status != 'Paid' THEN
            SET	payment_status = 'Paid';
            END IF;
          
        END IF;

        -- Update Paid and Due in the sale table
        UPDATE sale
        SET Paid = total_paid, Due = total_due,payment = 			payment_status
        WHERE sale_id = NEW.sale_id;
         
    END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_createdat_insert` BEFORE INSERT ON `payment_detail` FOR EACH ROW BEGIN
    IF NEW.created_at IS NULL THEN
        SET NEW.created_at = NOW();
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_paid_due` AFTER UPDATE ON `payment_detail` FOR EACH ROW BEGIN
    DECLARE total_paid DECIMAL(10, 2);
    DECLARE total_due DECIMAL(10, 2);
    DECLARE payment_status VARCHAR(255);

    -- Get current Paid and Due values for the sale
    SELECT Paid, Due,payment INTO total_paid, total_due,payment_status
    FROM sale
    WHERE sale_id = NEW.sale_id;
	IF payment_status != 'Paid' THEN
    
    -- Update Paid and Due with the new values
    SET total_paid = total_paid + NEW.valueplus;
    SET total_due = total_due - NEW.valueplus;
    
    -- Make sure valueplus is positive and status is 'access'
     IF NEW.valueplus > 0 AND NEW.status = 'success' THEN
        -- Check if total_due is negative and reset it to 0
        IF total_due <= 0 THEN
      
            SET total_due = 0;
            IF payment_status != 'Paid' THEN
            SET	payment_status = 'Paid';
            END IF;
          
        END IF;

        -- Update Paid and Due in the sale table
        UPDATE sale
        SET Paid = total_paid, Due = total_due,payment = 			payment_status
        WHERE sale_id = NEW.sale_id;
         
    END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_return_detail`
--

CREATE TABLE `payment_return_detail` (
  `id` bigint(20) NOT NULL,
  `payment_name` varchar(20) NOT NULL,
  `return_id` bigint(20) NOT NULL,
  `valueplus` decimal(10,2) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payment_return_detail`
--

INSERT INTO `payment_return_detail` (`id`, `payment_name`, `return_id`, `valueplus`, `status`, `created_at`, `updated_at`) VALUES
(10, 'Cash', 141, 108.00, 'Complete', '2023-09-05 16:05:43', '2023-09-05 16:05:43'),
(11, 'Cash', 142, 5400.00, 'Complete', '2023-09-05 16:06:37', '2023-09-05 16:06:37'),
(12, 'Cash', 143, 0.00, 'Complete', '2023-09-05 16:18:55', '2023-09-05 16:18:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_type`
--

CREATE TABLE `payment_type` (
  `id` bigint(20) NOT NULL,
  `typepayment` varchar(50) NOT NULL,
  `no_id` int(20) DEFAULT NULL,
  `theface` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payment_type`
--

INSERT INTO `payment_type` (`id`, `typepayment`, `no_id`, `theface`, `description`, `image`) VALUES
(1, 'Cash', NULL, 'Cash for image', NULL, NULL),
(2, 'Debit', 1015694586, 'Debit', NULL, NULL),
(3, 'MoMo', 393643357, 'MoMo ', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `id` bigint(20) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `minium_quantity` decimal(10,2) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `categoryproduct_id` int(11) DEFAULT NULL,
  `subcategoryproduct_id` int(11) DEFAULT NULL,
  `slug` varchar(1000) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `type_product` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id`, `sku`, `name`, `price`, `minium_quantity`, `quantity`, `unit`, `tax`, `discount`, `status`, `description`, `categoryproduct_id`, `subcategoryproduct_id`, `slug`, `updated_at`, `created_at`, `created_by`, `short_description`, `type_product`) VALUES
(103, 'QR_001', 'Ốc trắng to', 2.00, 1.00, 100.00, 'USD', 0.20, 0.00, 'Open', '<p><span style=\"font-size:11pt\"><span style=\"font-family:Montserrat,sans-serif\"><span style=\"color:#000000\">River Shell Button - 2 lỗ kh&acirc;u</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><span style=\"font-size:11pt\"><span style=\"font-family:Montserrat,sans-serif\"><span style=\"color:#000000\">Sizes - 12mm, 15mm, 18mm, 20mm, 23mm, 25mm, 34mm</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><span style=\"font-size:11pt\"><span style=\"font-family:Montserrat,sans-serif\"><span style=\"color:#000000\">L&agrave;m từ chất liệu thi&ecirc;n nhi&ecirc;n</span></span></span></p>\r\n\r\n<p><img src=\"/ckfinder/userfiles/images/reivershell.jpg\" style=\"height:350px; width:350px\" /></p>\r\n\r\n<p><span style=\"font-size:11pt\"><span style=\"font-family:Montserrat,sans-serif\"><span style=\"color:#000000\">Độ d&agrave;y v&agrave; m&agrave;u sắc được giữ nguy&ecirc;n như chất liệu tự nhi&ecirc;n của n&oacute;. N&uacute;t Rivershell l&agrave; một loại n&uacute;t d&ugrave;ng trong may v&aacute;, thủ c&ocirc;ng được l&agrave;m từ vỏ c&aacute;c lo&agrave;i nhuyễn thể s&ocirc;ng, điển h&igrave;nh l&agrave; trai nước ngọt. Những chiếc c&uacute;c n&agrave;y được biết đến với vẻ ngo&agrave;i tự nhi&ecirc;n v&agrave; thanh lịch, ch&uacute;ng thường được sử dụng trong thiết kế thời trang v&agrave; may mặc.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n', 16, 0, 'oc-trang-to', '2024-04-14 14:03:54', '2024-04-14 21:03:54', 'admin', 'Loại nút áo rivershell, nút áo với ốc trắng loại lớn', 'New'),
(104, 'QR_002', 'Ốc trắng dày ', 1.50, 1.00, 100.00, 'USD', 0.00, 0.00, 'Open', '<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">N&uacute;t &aacute;o rivershell</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">Sizes - 12mm, 15mm, 18mm, 20mm, 23mm, 25mm, 34mm</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">L&agrave;m từ 100% vỏ ốc thi&ecirc;n nhi&ecirc;n.</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">Đặc điểm của n&uacute;t Rivershell:</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">Chất liệu: Được l&agrave;m từ c&aacute;c lớp b&ecirc;n trong của vỏ nhuyễn thể s&ocirc;ng, chẳng hạn như trai nước ngọt.</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">H&igrave;nh thức b&ecirc;n ngo&agrave;i: Thường c&oacute; lớp ho&agrave;n thiện b&oacute;ng, &oacute;ng &aacute;nh, c&oacute; thể thay đổi m&agrave;u sắc t&ugrave;y thuộc v&agrave;o lo&agrave;i nhuyễn thể được sử dụng.</span></span></span></p>\r\n\r\n<p><img src=\"/ckfinder/userfiles/images/rivershell2.jpg\" style=\"height:408px; width:612px\" /></p>\r\n\r\n<p>&nbsp;</p>\r\n', 16, 0, 'oc-trang-day', '2024-04-14 14:10:14', '2024-04-14 21:10:14', 'admin', 'Loại nút áo rivershell, có các loại màu sắc theo sở thích của người dùng', 'New'),
(105, 'QR_003', 'Ốc trắng loại 1', 2.50, 1.00, 100.00, 'USD', 0.00, 0.00, 'Open', '<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">N&uacute;t &aacute;o rivershell</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">Sizes - 12mm, 15mm, 18mm, 20mm, 23mm, 25mm, 34mm</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">Độ bền: N&uacute;t Rivershell nh&igrave;n chung c&oacute; độ bền cao, mặc d&ugrave; ch&uacute;ng c&oacute; thể dễ bị sứt mẻ hoặc nứt nếu kh&ocirc;ng được xử l&yacute; cẩn thận.</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">T&iacute;nh linh hoạt: Những chiếc c&uacute;c n&agrave;y rất linh hoạt v&agrave; c&oacute; thể được sử dụng cho nhiều loại quần &aacute;o, phụ kiện v&agrave; đồ thủ c&ocirc;ng.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">T&iacute;nh bền vững: N&uacute;t Rivershell được coi l&agrave; lựa chọn tự nhi&ecirc;n v&agrave; bền vững so với n&uacute;t nhựa. Tuy nhi&ecirc;n, c&aacute;c hoạt động t&igrave;m nguồn cung ứng cần được xem x&eacute;t để đảm bảo rằng việc sử dụng vỏ s&ocirc;ng kh&ocirc;ng t&aacute;c động ti&ecirc;u cực đến hệ sinh th&aacute;i địa phương.</span></span></span></p>\r\n\r\n<p><img src=\"/ckfinder/userfiles/images/rivershell3.jpg\" style=\"height:600px; width:600px\" /></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">C&ocirc;ng dụng:</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">Quần &aacute;o: N&uacute;t Rivershell thường được sử dụng trong quần &aacute;o như &aacute;o sơ mi, &aacute;o c&aacute;nh, v&aacute;y v&agrave; &aacute;o kho&aacute;c ngo&agrave;i.</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">Phụ kiện: Ch&uacute;ng c&oacute; thể được sử dụng trong c&aacute;c phụ kiện như t&uacute;i x&aacute;ch v&agrave; mũ.</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">Đồ thủ c&ocirc;ng: N&uacute;t vỏ s&ograve; cũng c&oacute; thể được sử dụng trong c&aacute;c dự &aacute;n thủ c&ocirc;ng kh&aacute;c nhau nhằm mục đ&iacute;ch trang tr&iacute;.</span></span></span></p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n', 16, 0, 'oc-trang-loai-1', '2024-04-14 14:16:20', '2024-04-14 21:16:20', 'admin', 'Nút áo RiverShell mang lại những trải nghiệm về màu sắc, kiểu dáng, là sự lựa chọn của mọi người', 'Sale'),
(106, 'QR_004', 'Ốc vàng loại 1', 1.50, 1.00, 100.00, 'USD', 0.00, 0.00, 'Open', '<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#000000\">&quot;N&uacute;t MOP&quot; d&ugrave;ng để chỉ một chiếc n&uacute;t được l&agrave;m từ x&agrave; cừ (MOP), l&agrave; một vật liệu tự nhi&ecirc;n c&oacute; nguồn gốc từ lớp l&oacute;t b&ecirc;n trong của một số vỏ nhuyễn thể. Những chiếc c&uacute;c khảm x&agrave; cừ đ&atilde; được sử dụng trong nhiều năm trong sản xuất h&agrave;ng may mặc v&agrave; thời trang nhờ độ b&oacute;ng v&agrave; sức hấp dẫn thị gi&aacute;c độc đ&aacute;o của ch&uacute;ng. Dưới đ&acirc;y l&agrave; một số th&ocirc;ng tin th&ecirc;m về n&uacute;t lau nh&agrave;:</span></span></span></p>\r\n\r\n<p><img src=\"/ckfinder/userfiles/images/mop3.jpg\" style=\"height:225px; width:225px\" /></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#000000\">Đặc điểm của n&uacute;t MOP:</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#000000\">Chất liệu: Được l&agrave;m từ lớp l&oacute;t b&ecirc;n trong của vỏ nhuyễn thể như h&agrave;u, b&agrave;o ngư. Lớp l&oacute;t n&agrave;y được gọi l&agrave; x&agrave; cừ.</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#000000\">Ngoại h&igrave;nh: Ch&uacute;ng c&oacute; bề mặt &oacute;ng &aacute;nh v&agrave; b&oacute;ng, mang lại vẻ ngo&agrave;i độc đ&aacute;o v&agrave; thanh lịch.</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#000000\">Độ bền: X&agrave; cừ l&agrave; chất liệu tương đối chắc chắn, gi&uacute;p c&aacute;c n&uacute;t n&agrave;y bền bỉ khi sử dụng thường xuy&ecirc;n.</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16px\"><span style=\"color:#000000\">Đa dạng: N&uacute;t lau nh&agrave; c&oacute; nhiều h&igrave;nh dạng, k&iacute;ch cỡ v&agrave; m&agrave;u sắc kh&aacute;c nhau, t&ugrave;y thuộc v&agrave;o loại vỏ được sử dụng.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n', 26, 0, 'oc-vang-loai-1', '2024-04-14 14:31:48', '2024-04-14 21:31:48', 'admin', 'Nút áo MOP đậm màu thiên nhiên, phản ánh màu sắc của thiên nhiên lên trang sức', 'Sale'),
(107, 'QR_005', 'Ốc vàng loại 2', 3.00, 1.00, 100.00, 'USD', 0.00, 0.00, 'Open', '<p style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">&quot;N&uacute;t MOP&quot; d&ugrave;ng để chỉ một chiếc n&uacute;t được l&agrave;m từ x&agrave; cừ (MOP), l&agrave; một vật liệu tự nhi&ecirc;n c&oacute; nguồn gốc từ lớp l&oacute;t b&ecirc;n trong của một số vỏ nhuyễn thể. Những chiếc c&uacute;c khảm x&agrave; cừ đ&atilde; được sử dụng trong nhiều năm trong sản xuất h&agrave;ng may mặc v&agrave; thời trang nhờ độ b&oacute;ng v&agrave; sức hấp dẫn thị gi&aacute;c độc đ&aacute;o của ch&uacute;ng. Dưới đ&acirc;y l&agrave; một số th&ocirc;ng tin th&ecirc;m về n&uacute;t lau nh&agrave;:</span></span></span></p>\r\n\r\n<p><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Sang trọng: Độ b&oacute;ng v&agrave; độ b&oacute;ng tự nhi&ecirc;n của ch&uacute;ng khiến ch&uacute;ng trở th&agrave;nh lựa chọn phổ biến cho quần &aacute;o chất lượng cao v&agrave; sang trọng.</span></span></span></p>\r\n\r\n<p><img src=\"/ckfinder/userfiles/images/Mop.jpg\" style=\"height:807px; width:807px\" /></p>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">C&ocirc;ng dụng:</span></span></span></li>\r\n</ul>\r\n\r\n<ul style=\"margin-left:40px\">\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Quần &aacute;o: N&uacute;t MOP thường được sử dụng tr&ecirc;n c&aacute;c loại &aacute;o sơ mi, &aacute;o c&aacute;nh, &aacute;o kho&aacute;c v&agrave; c&aacute;c sản phẩm may mặc cao cấp kh&aacute;c.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Phụ kiện: Ch&uacute;ng cũng c&oacute; thể được sử dụng trong c&aacute;c phụ kiện như t&uacute;i x&aacute;ch v&agrave; mũ.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Đồ thủ c&ocirc;ng: N&uacute;t MOP c&oacute; thể được sử dụng trong c&aacute;c dự &aacute;n chế tạo v&igrave; vẻ đẹp v&agrave; vẻ ngo&agrave;i độc đ&aacute;o của ch&uacute;ng.</span></span></span></li>\r\n</ul>\r\n\r\n<p><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Nguồn cung ứng v&agrave; t&iacute;nh bền vững:</span></span></span></p>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Nguồn cung ứng: N&uacute;t MOP thường được l&agrave;m từ vỏ động vật th&acirc;n mềm được thu hoạch để lấy thịt. Điều quan trọng l&agrave; phải xem x&eacute;t c&aacute;c hoạt động t&igrave;m nguồn cung ứng bền vững để giảm thiểu t&aacute;c động đến m&ocirc;i trường.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Đạo đức: Việc t&igrave;m nguồn cung ứng c&oacute; đạo đức l&agrave; rất quan trọng khi chọn n&uacute;t MOP để đảm bảo rằng c&aacute;c hoạt động thu hoạch kh&ocirc;ng g&acirc;y hại cho quần thể hoặc hệ sinh th&aacute;i nhuyễn thể.</span></span></span></li>\r\n</ul>\r\n\r\n<p><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Nh&igrave;n chung, khuy x&agrave; cừ l&agrave; sự lựa chọn cổ điển v&agrave; thanh lịch cho quần &aacute;o v&agrave; phụ kiện. Ch&uacute;ng tạo th&ecirc;m n&eacute;t sang trọng cho bất kỳ m&oacute;n đồ n&agrave;o ch&uacute;ng được sử dụng.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n', 26, 0, 'oc-vang-loai-2', '2024-04-14 16:20:15', '2024-04-14 23:20:15', 'admin', 'Ốc vàng loại 2 thuộc loại nút áo MOP, phù hợp hơn cho những người có sở thích thiên ', 'Other'),
(108, 'QR_006', 'Ốc đen ', 3.50, 1.00, 100.00, 'USD', 0.00, 0.00, 'Open', '<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#e74c3c\"><strong>N&uacute;t vỏ s&ograve; Trocas</strong></span><span style=\"color:#000000\"> được l&agrave;m từ vỏ của lo&agrave;i Trochus, một loại nhuyễn thể biển thường được gọi l&agrave; vỏ top hay vỏ Trocas. Những chiếc c&uacute;c n&agrave;y được biết đến với vẻ ngo&agrave;i độc đ&aacute;o v&agrave; đẹp mắt, khiến ch&uacute;ng trở n&ecirc;n phổ biến trong thời trang v&agrave; may mặc.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h3><span style=\"font-family:Tahoma,Geneva,sans-serif\"><strong><span style=\"font-size:11pt\"><span style=\"color:#000000\">Đặc điểm của N&uacute;t vỏ s&ograve; Trocas:</span></span></strong></span></h3>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">Chất liệu: Được l&agrave;m từ vỏ của lo&agrave;i nhuyễn thể biển Trochus.</span></span></span></p>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:11pt\"><span style=\"color:#000000\">H&igrave;nh thức b&ecirc;n ngo&agrave;i: Vỏ s&ograve; Trocas c&oacute; vẻ ngo&agrave;i tự nhi&ecirc;n, &oacute;ng &aacute;nh với nhiều m&agrave;u sắc gồm kem, be, x&aacute;m v&agrave; xanh lục. C&aacute;c hoa văn v&agrave; m&agrave;u sắc độc đ&aacute;o của vỏ mang lại cho c&aacute;c n&uacute;t một vẻ ngo&agrave;i trang nh&atilde;.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><img src=\"/ckfinder/userfiles/images/v%E1%BB%8F%20s%C3%B2%20trocas.jpg\" style=\"height:364px; width:550px\" /></p>\r\n', 26, 0, 'oc-den', '2024-04-14 16:33:38', '2024-04-14 23:33:38', 'admin', 'Vỏ ốc trocas, với vẻ đẹp sặc sỡ và sáng bóng, đã thu hút sự chú ý của người ta từ thời cổ đại. Với sắc màu độc đáo và hình dáng độc đáo, vỏ ốc trocas không chỉ là một hiện vật tự nhiên độc đáo mà còn mang lại sự kỳ diệu của thiên nhiên.', 'Other'),
(109, 'QR_007', 'Ốc đen mỏng', 1.50, 1.00, 100.00, 'USD', 0.00, 0.00, 'Closed', '<p><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#e74c3c\">N&uacute;t vỏ s&ograve; Trocas</span><span style=\"color:#000000\"> được l&agrave;m từ vỏ của lo&agrave;i Trochus, một loại nhuyễn thể biển thường được gọi l&agrave; vỏ top hay vỏ Trocas. Những chiếc c&uacute;c n&agrave;y được biết đến với vẻ ngo&agrave;i độc đ&aacute;o v&agrave; đẹp mắt, khiến ch&uacute;ng trở n&ecirc;n phổ biến trong thời trang v&agrave; may mặc.</span></span></span></p>\r\n\r\n<p><img src=\"/ckfinder/userfiles/images/trocasshell.jpg\" style=\"height:376px; width:500px\" /></p>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">Đặc điểm của N&uacute;t vỏ s&ograve; Trocas:</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">Chất liệu: Được l&agrave;m từ vỏ của lo&agrave;i nhuyễn thể biển Trochus.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">H&igrave;nh thức b&ecirc;n ngo&agrave;i: Vỏ s&ograve; Trocas c&oacute; vẻ ngo&agrave;i tự nhi&ecirc;n, &oacute;ng &aacute;nh với nhiều m&agrave;u sắc gồm kem, be, x&aacute;m v&agrave; xanh lục. C&aacute;c hoa văn v&agrave; m&agrave;u sắc độc đ&aacute;o của vỏ mang lại cho c&aacute;c n&uacute;t một vẻ ngo&agrave;i trang nh&atilde;.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">Độ bền: Vỏ Trocas tương đối bền v&agrave; tạo ra c&aacute;c n&uacute;t bấm chắc chắn, c&oacute; thể chịu được việc sử dụng thường xuy&ecirc;n.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">Đa dạng: N&uacute;t vỏ s&ograve; Trocas c&oacute; nhiều h&igrave;nh dạng, k&iacute;ch cỡ v&agrave; m&agrave;u sắc kh&aacute;c nhau, t&ugrave;y thuộc v&agrave;o loại vỏ Trochus cụ thể được sử dụng.</span></span></span></li>\r\n</ul>\r\n\r\n<p><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#e74c3c\">T&iacute;nh bền vững</span><span style=\"color:#e67e22\">:</span><span style=\"color:#000000\"> Vỏ s&ograve; Trocas l&agrave; nguồn t&agrave;i nguy&ecirc;n thi&ecirc;n nhi&ecirc;n v&agrave; c&oacute; thể t&aacute;i tạo, nhưng cần xem x&eacute;t c&aacute;c biện ph&aacute;p khai th&aacute;c bền vững để bảo vệ hệ sinh th&aacute;i biển.</span></span></span></p>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">C&ocirc;ng dụng:</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">Quần &aacute;o: N&uacute;t vỏ s&ograve; Trocas thường được sử dụng trong &aacute;o sơ mi, &aacute;o c&aacute;nh v&agrave; c&aacute;c sản phẩm may mặc kh&aacute;c chất lượng cao. Ch&uacute;ng mang lại n&eacute;t thanh lịch v&agrave; sang trọng cho trang phục.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">Phụ kiện: Ch&uacute;ng cũng c&oacute; thể được sử dụng trong c&aacute;c phụ kiện như t&uacute;i x&aacute;ch, thắt lưng v&agrave; mũ.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#000000\">Đồ thủ c&ocirc;ng: N&uacute;t vỏ s&ograve; Trocas c&oacute; thể được sử dụng trong c&aacute;c dự &aacute;n chế tạo v&igrave; t&iacute;nh thẩm mỹ của ch&uacute;ng.</span></span></span></li>\r\n</ul>\r\n', 18, 0, 'oc-den-mong', '2024-04-14 16:42:55', '2024-04-14 23:42:55', 'admin', 'Vỏ ốc trocas, với vẻ đẹp sặc sỡ và sáng bóng, đã thu hút sự chú ý của người ta từ thời cổ đại. Với sắc màu độc đáo và hình dáng độc đáo, vỏ ốc trocas không chỉ là một hiện vật tự nhiên độc đáo mà còn mang lại sự kỳ diệu của thiên nhiên.', 'New'),
(110, 'QR_008', 'Ốc đen dày', 3.00, 1.00, 100.00, 'USD', 0.00, 0.00, 'Open', '<p><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#e74c3c\"><strong>N&uacute;t vỏ s&ograve; Trocas</strong></span><span style=\"color:#000000\"> được l&agrave;m từ vỏ của lo&agrave;i Trochus, một loại nhuyễn thể biển thường được gọi l&agrave; vỏ top hay vỏ Trocas. Những chiếc c&uacute;c n&agrave;y được biết đến với vẻ ngo&agrave;i độc đ&aacute;o v&agrave; đẹp mắt, khiến ch&uacute;ng trở n&ecirc;n phổ biến trong thời trang v&agrave; may mặc.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style=\"list-style-type:disc\"><span style=\"color:#e74c3c\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\">Đặc điểm của N&uacute;t vỏ s&ograve; Trocas:</span></span></span></p>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Chất liệu: Được l&agrave;m từ vỏ của lo&agrave;i nhuyễn thể biển Trochus.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">H&igrave;nh thức b&ecirc;n ngo&agrave;i: Vỏ s&ograve; Trocas c&oacute; vẻ ngo&agrave;i tự nhi&ecirc;n, &oacute;ng &aacute;nh với nhiều m&agrave;u sắc gồm kem, be, x&aacute;m v&agrave; xanh lục. C&aacute;c hoa văn v&agrave; m&agrave;u sắc độc đ&aacute;o của vỏ mang lại cho c&aacute;c n&uacute;t một vẻ ngo&agrave;i trang nh&atilde;.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Độ bền: Vỏ Trocas tương đối bền v&agrave; tạo ra c&aacute;c n&uacute;t bấm chắc chắn, c&oacute; thể chịu được việc sử dụng thường xuy&ecirc;n.</span></span></span></li>\r\n</ul>\r\n\r\n<p><img src=\"/ckfinder/userfiles/images/trocasshell2.jpg\" style=\"height:428px; width:570px\" /></p>\r\n\r\n<p style=\"list-style-type:disc\"><span style=\"color:#e74c3c\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\">Nguồn cung ứng v&agrave; t&iacute;nh bền vững:</span></span></span></p>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Nguồn cung ứng: Vỏ s&ograve; Trocas c&oacute; nguồn gốc từ c&aacute;c v&ugrave;ng nhiệt đới v&agrave; cận nhiệt đới nơi t&igrave;m thấy lo&agrave;i Trochus. Điều quan trọng l&agrave; phải đảm bảo rằng hoạt động khai th&aacute;c bền vững v&agrave; kh&ocirc;ng g&acirc;y hại cho sinh vật biển.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Đạo đức: T&igrave;m nguồn cung ứng c&oacute; đạo đức l&agrave; điều cần thiết để duy tr&igrave; quần thể lo&agrave;i Trochus khỏe mạnh v&agrave; bảo vệ m&ocirc;i trường biển.</span></span></span></li>\r\n</ul>\r\n\r\n<p><span style=\"font-size:11pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"color:#000000\">Nh&igrave;n chung, c&uacute;c vỏ s&ograve; Trocas l&agrave; một lựa chọn đẹp v&agrave; thanh lịch cho thời trang v&agrave; đồ thủ c&ocirc;ng, tạo th&ecirc;m n&eacute;t tự nhi&ecirc;n cho bất kỳ m&oacute;n đồ n&agrave;o ch&uacute;ng được sử dụng.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n', 18, 0, 'oc-den-day', '2024-04-14 16:47:48', '2024-04-14 23:47:48', 'admin', 'Vỏ ốc trocas, với vẻ đẹp sặc sỡ và sáng bóng, đã thu hút sự chú ý của người ta từ thời cổ đại. Với sắc màu độc đáo và hình dáng độc đáo, vỏ ốc trocas không chỉ là một hiện vật tự nhiên độc đáo mà còn mang lại sự kỳ diệu của thiên nhiên.', 'Other'),
(111, 'QR_009', 'Sò điệp loại 1', 4.00, 1.00, 100.00, 'USD', 0.00, 0.00, 'Closed', '<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">N&uacute;t &aacute;o từ vỏ s&ograve;, hay c&ograve;n gọi l&agrave; n&uacute;t &aacute;o từ vỏ ốc, l&agrave; loại n&uacute;t &aacute;o được l&agrave;m từ vỏ của c&aacute;c lo&agrave;i th&acirc;n mềm biển hoặc nước ngọt như ngọc trai, trai s&ocirc;ng, hoặc ốc Trocas. Loại n&uacute;t &aacute;o n&agrave;y c&oacute; vẻ đẹp tự nhi&ecirc;n v&agrave; được ưa chuộng trong thời trang v&agrave; may mặc.</span></span></span></p>\r\n\r\n<h3><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16.5pt\"><span style=\"color:#0d0d0d\"><strong>Đặc điểm của n&uacute;t &aacute;o từ vỏ s&ograve;:</strong></span></span></span></h3>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>Chất liệu</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: Được l&agrave;m từ vỏ của c&aacute;c lo&agrave;i th&acirc;n mềm như ngọc trai, trai s&ocirc;ng hoặc c&aacute;c lo&agrave;i ốc biển.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>Ngoại h&igrave;nh</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: N&uacute;t &aacute;o từ vỏ s&ograve; c&oacute; vẻ ngo&agrave;i b&oacute;ng mượt, lấp l&aacute;nh v&agrave; đa dạng m&agrave;u sắc, từ trắng, x&aacute;m, be cho đến c&aacute;c m&agrave;u xanh l&aacute;, hồng nhạt, v&agrave; t&iacute;m.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>Độ bền</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: Loại n&uacute;t &aacute;o n&agrave;y c&oacute; độ bền cao, c&oacute; thể sử dụng l&acirc;u d&agrave;i nếu được bảo quản đ&uacute;ng c&aacute;ch.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>Sự đa dạng</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: C&oacute; nhiều h&igrave;nh dạng, k&iacute;ch cỡ v&agrave; m&agrave;u sắc kh&aacute;c nhau t&ugrave;y thuộc v&agrave;o loại vỏ s&ograve; được sử dụng.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>T&iacute;nh bền vững</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: Vỏ s&ograve; l&agrave; t&agrave;i nguy&ecirc;n tự nhi&ecirc;n v&agrave; c&oacute; thể t&aacute;i tạo, tuy nhi&ecirc;n cần ch&uacute; &yacute; đến việc khai th&aacute;c bền vững để bảo vệ m&ocirc;i trường.</span></span></span><img src=\"/ckfinder/userfiles/images/s%C3%B2%20%C4%91i%E1%BB%87p%202.jpg\" style=\"height:500px; width:500px\" /></li>\r\n</ul>\r\n\r\n<h3><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16.5pt\"><span style=\"color:#0d0d0d\"><strong>Ứng dụng:</strong></span></span></span></h3>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>Quần &aacute;o</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: N&uacute;t &aacute;o từ vỏ s&ograve; thường được sử dụng trong c&aacute;c loại &aacute;o sơ mi, v&aacute;y, v&agrave; quần &aacute;o cao cấp, tạo điểm nhấn sang trọng.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>Phụ kiện</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: Ngo&agrave;i ra, n&uacute;t &aacute;o n&agrave;y c&ograve;n được sử dụng trong c&aacute;c phụ kiện như t&uacute;i x&aacute;ch, mũ, v&agrave; thắt lưng.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>Thủ c&ocirc;ng</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: N&uacute;t &aacute;o từ vỏ s&ograve; cũng c&oacute; thể được sử dụng trong c&aacute;c dự &aacute;n thủ c&ocirc;ng mỹ nghệ nhờ vẻ đẹp tự nhi&ecirc;n của ch&uacute;ng.</span></span></span></li>\r\n</ul>\r\n\r\n<h3><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:16.5pt\"><span style=\"color:#0d0d0d\"><strong>Nguồn gốc v&agrave; t&iacute;nh bền vững:</strong></span></span></span></h3>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>Nguồn gốc</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: Vỏ s&ograve; được thu thập từ c&aacute;c v&ugrave;ng biển v&agrave; s&ocirc;ng suối nơi c&aacute;c lo&agrave;i th&acirc;n mềm sinh sống.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\"><strong>Đạo đức</strong></span></span><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">: Đảm bảo việc khai th&aacute;c bền vững l&agrave; cần thiết để bảo tồn c&aacute;c quần thể lo&agrave;i th&acirc;n mềm v&agrave; bảo vệ m&ocirc;i trường.</span></span></span></li>\r\n</ul>\r\n\r\n<p><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"font-size:12pt\"><span style=\"color:#0d0d0d\">Nh&igrave;n chung, n&uacute;t &aacute;o từ vỏ s&ograve; l&agrave; lựa chọn đẹp mắt v&agrave; sang trọng cho thời trang v&agrave; thủ c&ocirc;ng, mang lại vẻ tự nhi&ecirc;n v&agrave; tinh tế cho bất kỳ m&oacute;n đồ n&agrave;o ch&uacute;ng được sử dụng.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>\r\n', 19, 0, 'so-diep-loai-1', '2024-04-14 16:52:18', '2024-04-14 23:52:18', 'admin', 'Nút áo từ vỏ sò điệp là một biểu tượng của sự tinh tế và sáng tạo từ tự nhiên. Từng mảnh vỏ sò được chế tác và mài mọng, tạo ra những chiếc nút áo độc đáo, làm nổi bật vẻ đẹp tự nhiên và tinh tế trong trang phục.', 'New'),
(112, 'QR_010', 'Vỏ ốc ', 1.30, 1.00, 100.00, 'USD', 0.00, 0.00, 'Closed', '<p><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\">Vỏ ốc l&agrave; một nguy&ecirc;n liệu tự nhi&ecirc;n được sử dụng để l&agrave;m n&ecirc;n n&uacute;t &aacute;o trong ng&agrave;nh thời trang v&agrave; may mặc. C&aacute;c loại vỏ ốc được sử dụng thường l&agrave; từ c&aacute;c lo&agrave;i th&acirc;n mềm biển hoặc nước ngọt như ngọc trai, trai s&ocirc;ng, hoặc ốc Trocas. Dưới đ&acirc;y l&agrave; một số th&ocirc;ng tin về việc sử dụng vỏ ốc để l&agrave;m n&uacute;t &aacute;o:</span></span></span></p>\r\n\r\n<h3><span style=\"font-size:18px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Đặc điểm của n&uacute;t &aacute;o l&agrave;m từ vỏ ốc:</strong></span></span></span></h3>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Chất liệu</strong>: Được l&agrave;m từ vỏ của c&aacute;c lo&agrave;i th&acirc;n mềm biển hoặc nước ngọt.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Ngoại h&igrave;nh</strong>: Vỏ ốc c&oacute; vẻ ngo&agrave;i b&oacute;ng mượt v&agrave; lấp l&aacute;nh với c&aacute;c m&agrave;u sắc tự nhi&ecirc;n như trắng, x&aacute;m, be, xanh, hồng, hoặc t&iacute;m t&ugrave;y thuộc v&agrave;o loại vỏ được sử dụng.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Độ bền</strong>: N&uacute;t &aacute;o từ vỏ ốc thường bền v&agrave; c&oacute; thể chịu được sử dụng h&agrave;ng ng&agrave;y nếu được bảo quản đ&uacute;ng c&aacute;ch.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Sự đa dạng</strong>: N&uacute;t &aacute;o từ vỏ ốc c&oacute; nhiều h&igrave;nh dạng, k&iacute;ch cỡ v&agrave; m&agrave;u sắc kh&aacute;c nhau, mang lại sự đa dạng cho c&aacute;c sản phẩm may mặc.</span></span></span></li>\r\n</ul>\r\n\r\n<h3><span style=\"font-size:18px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Ứng dụng:</strong></span></span></span></h3>\r\n\r\n<ul>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Quần &aacute;o</strong>: N&uacute;t &aacute;o từ vỏ ốc thường được sử dụng trong c&aacute;c loại &aacute;o sơ mi, &aacute;o kho&aacute;c, v&aacute;y, v&agrave; c&aacute;c trang phục cao cấp.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Phụ kiện</strong>: Ch&uacute;ng cũng được d&ugrave;ng trong c&aacute;c phụ kiện như t&uacute;i x&aacute;ch, mũ, v&agrave; gi&agrave;y d&eacute;p.</span></span></span></li>\r\n	<li style=\"list-style-type:disc\"><span style=\"font-size:14px\"><span style=\"font-family:Tahoma,Geneva,sans-serif\"><span style=\"color:#0d0d0d\"><strong>Thủ c&ocirc;ng</strong>: Vỏ ốc cũng c&oacute; thể được sử dụng trong c&aacute;c dự &aacute;n thủ c&ocirc;ng mỹ nghệ, như trang sức hoặc trang tr&iacute;.</span></span></span></li>\r\n</ul>\r\n\r\n<p><img src=\"/ckfinder/userfiles/images/v%E1%BB%8F%20%E1%BB%91c%202.jpg\" style=\"height:291px; width:400px\" /></p>\r\n', 20, 0, 'vo-oc', '2024-04-14 16:55:49', '2024-04-14 23:55:49', 'admin', 'Nút áo từ vỏ ốc là một biểu tượng của sự tái chế và sáng tạo từ nguyên liệu tự nhiên. Từ những viên vỏ ốc được tinh tế chế tác, nghệ nhân đã tạo ra những chiếc nút áo độc đáo, mang trong mình vẻ đẹp tự nhiên và sự độc đáo không gì sánh bằng.', 'Other');

--
-- Bẫy `product`
--
DELIMITER $$
CREATE TRIGGER `SetCreatedAt` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    IF NEW.created_at IS NULL THEN
        SET NEW.created_at = NOW();
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `SetCreatedAt_customer` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    SET NEW.created_at = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `SetCreatedAt_product` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    SET NEW.created_at = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `SetCreatedAt_usercustomer` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    SET NEW.created_at = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_product_delete` AFTER DELETE ON `product` FOR EACH ROW BEGIN
    DELETE FROM image_product WHERE product_id = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quotation`
--

CREATE TABLE `quotation` (
  `quotation_id` bigint(20) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `grand_total` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `quotation`
--

INSERT INTO `quotation` (`quotation_id`, `reference`, `email`, `status`, `total`, `tax`, `discount`, `grand_total`, `description`, `created_at`, `updated_at`) VALUES
(8, 'IVO001', 'hoangnhan@gmail.com', 'Ordered', 1950.00, 0.30, 0.10, 2340.00, 'aaabbb', '2023-09-07 00:00:00', '2023-09-07 13:56:15'),
(9, 'IVO002', '2156210125@gmail.com', 'Send', 349.50, 0.10, 0.00, 384.45, 'bbb', '2023-09-07 00:00:00', '2023-09-07 14:04:06'),
(11, 'IVO003', 'hoangnhan@gmail.com', 'Send', 195.00, 0.10, 0.33, 150.15, 'sdssd', '2023-09-07 00:00:00', '2023-09-07 15:05:26'),
(12, 'IVO004', '2156210125@gmail.com', 'Ordered', 150.00, 0.10, 0.25, 127.50, '', '2024-04-19 00:00:00', '2024-04-04 02:55:27');

--
-- Bẫy `quotation`
--
DELIMITER $$
CREATE TRIGGER `SetCreatedAt_quotationfinal` BEFORE INSERT ON `quotation` FOR EACH ROW BEGIN
    IF NEW.created_at IS NULL THEN
        SET NEW.created_at = NOW();
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_quotation_reference` BEFORE INSERT ON `quotation` FOR EACH ROW BEGIN
    DECLARE next_id INT;

    -- Lấy phần số thứ tự tiếp theo từ chuỗi reference
    SELECT IFNULL(MAX(CAST(SUBSTRING_INDEX(reference, 'IVO', -1) AS UNSIGNED)), 0) INTO next_id FROM quotation;

    -- Tính toán giá trị tiếp theo
    SET next_id = next_id + 1;

    -- Nếu không có giá trị tiếp theo hoặc reference của bản ghi mới là NULL, bắt đầu từ SLO001
    IF next_id IS NULL AND NEW.reference IS NULL THEN
        SET NEW.reference = 'IVO001';
    ELSE
        -- Tạo giá trị mới cho reference với định dạng 'SLOxxx'
        SET NEW.reference = CONCAT('IVO', LPAD(next_id, 3, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quotation_detail`
--

CREATE TABLE `quotation_detail` (
  `quotation_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `minium_quantity` decimal(10,2) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `quotation_detail`
--

INSERT INTO `quotation_detail` (`quotation_id`, `product_id`, `price`, `tax`, `discount`, `minium_quantity`, `quantity`, `total`) VALUES
(8, 68, 150.00, 0.00, 0.00, 1.00, 7.00, 1050.00),
(8, 69, 150.00, 0.00, 0.00, 1.00, 6.00, 900.00),
(9, 64, 15.00, 0.20, 0.10, 1.00, 3.00, 49.50),
(9, 71, 15.00, 0.00, 0.00, 1.00, 2.00, 30.00),
(9, 72, 150.00, 0.00, 0.10, 1.00, 2.00, 270.00),
(11, 69, 150.00, 0.00, 0.00, 1.00, 1.00, 150.00),
(11, 71, 15.00, 0.00, 0.00, 1.00, 3.00, 45.00),
(12, 69, 150.00, 0.00, 0.00, 1.00, 1.00, 150.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sale`
--

CREATE TABLE `sale` (
  `sale_id` bigint(20) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `payment` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `ship` decimal(10,2) DEFAULT NULL,
  `grand_total` decimal(10,2) DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT NULL,
  `due` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `biller` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `discount_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sale`
--

INSERT INTO `sale` (`sale_id`, `reference`, `email`, `status`, `payment`, `total`, `tax`, `ship`, `grand_total`, `paid`, `due`, `description`, `biller`, `created_at`, `updated_at`, `discount_id`) VALUES
(194, 'SLO001', '2156210100@gmail.com', 'Pending', 'Due', 1.30, NULL, NULL, 1.30, NULL, NULL, NULL, NULL, '2024-04-15 02:52:11', '2024-04-14 19:52:11', NULL);

--
-- Bẫy `sale`
--
DELIMITER $$
CREATE TRIGGER `SetCreatedAt_sale` BEFORE INSERT ON `sale` FOR EACH ROW BEGIN
    IF NEW.created_at IS NULL THEN
        SET NEW.created_at = NOW();
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_sale_nottification` AFTER INSERT ON `sale` FOR EACH ROW BEGIN
    -- Lấy email từ bảng sale
    DECLARE userEmail VARCHAR(255);
    SELECT email INTO userEmail FROM sale WHERE sale_id = NEW.sale_id;

    -- Chèn thông báo vào bảng notifications
    INSERT INTO notification (email, message)
    VALUES (userEmail, 'New order created');
    DELETE FROM notification
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_sale_reference` BEFORE INSERT ON `sale` FOR EACH ROW BEGIN
    DECLARE next_id INT;

    -- Lấy phần số thứ tự tiếp theo từ chuỗi reference
    SELECT IFNULL(MAX(CAST(SUBSTRING_INDEX(reference, 'SLO', -1) AS UNSIGNED)), 0) INTO next_id FROM sale;

    -- Tính toán giá trị tiếp theo
    SET next_id = next_id + 1;

    -- Nếu không có giá trị tiếp theo hoặc reference của bản ghi mới là NULL, bắt đầu từ SLO001
    IF next_id IS NULL AND NEW.reference IS NULL THEN
        SET NEW.reference = 'SLO001';
    ELSE
        -- Tạo giá trị mới cho reference với định dạng 'SLOxxx'
        SET NEW.reference = CONCAT('SLO', LPAD(next_id, 3, '0'));
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inserttrigger_status_payment` BEFORE INSERT ON `sale` FOR EACH ROW BEGIN
    IF NEW.status = 'Complete' THEN
        SET NEW.payment = 'Paid';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_payment_status` BEFORE UPDATE ON `sale` FOR EACH ROW BEGIN
    IF NEW.status = 'Complete' THEN
        SET NEW.payment = 'Paid';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sale_detail`
--

CREATE TABLE `sale_detail` (
  `sale_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `minium_quantity` decimal(10,2) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sale_detail`
--

INSERT INTO `sale_detail` (`sale_id`, `product_id`, `price`, `tax`, `discount`, `minium_quantity`, `quantity`, `total`) VALUES
(194, 112, 1.30, NULL, NULL, NULL, 1.00, 1.30);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sale_return`
--

CREATE TABLE `sale_return` (
  `id` bigint(20) NOT NULL,
  `sale_id` bigint(20) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) NOT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `payment` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `grand_total` decimal(10,2) DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT NULL,
  `due` decimal(10,2) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sale_return`
--

INSERT INTO `sale_return` (`id`, `sale_id`, `reference`, `email`, `product_id`, `quantity`, `status`, `payment`, `total`, `tax`, `discount`, `grand_total`, `paid`, `due`, `reason`, `created_at`, `updated_at`) VALUES
(141, 134, 'SRT001', '21562101253@gmail.com', 72, 1.00, 'Complete', 'Paid', 135.00, 0.00, 27.00, 108.00, 108.00, 0.00, 'dfdfd', '2023-09-05 00:00:00', '2023-09-05 04:07:27'),
(142, 117, 'SRT001', '2156210125@gmail.com', 67, 4.00, 'Complete', 'Paid', 5400.00, 0.20, 1080.00, 5400.00, 5400.00, 0.00, 'sdsdssds', '2024-04-05 00:00:00', '2023-09-05 16:06:37'),
(143, 134, 'SRT002', '21562101253@gmail.com', 73, 2.00, 'Pending', 'Paid', 100.00, 0.00, 20.00, 80.00, 0.00, 80.00, '', '2023-09-09 00:00:00', '2023-09-05 16:18:55');

--
-- Bẫy `sale_return`
--
DELIMITER $$
CREATE TRIGGER `SetCreatedAt_sale_return` BEFORE INSERT ON `sale_return` FOR EACH ROW BEGIN
    IF NEW.created_at IS NULL THEN
        SET NEW.created_at = NOW();
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_sale_reference__return` BEFORE INSERT ON `sale_return` FOR EACH ROW BEGIN
    DECLARE next_id INT;

    -- Lấy phần số thứ tự tiếp theo từ chuỗi reference
    SELECT IFNULL(MAX(CAST(SUBSTRING_INDEX(reference, 'SRT', -1) AS UNSIGNED)), 0) INTO next_id FROM sale_return;

    -- Tính toán giá trị tiếp theo
    SET next_id = next_id + 1;

    -- Nếu không có giá trị tiếp theo hoặc reference của bản ghi mới là NULL, bắt đầu từ SRT001
    IF next_id IS NULL AND NEW.reference IS NULL THEN
        SET NEW.reference = 'SRT001';
    ELSE
        -- Tạo giá trị mới cho reference với định dạng 'SRTxxx'
        SET NEW.reference = CONCAT('SRT', LPAD(next_id, 3, '0'));
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inserttrigger_status_payment_return` BEFORE INSERT ON `sale_return` FOR EACH ROW BEGIN
    IF NEW.status = 'Complete' THEN
        SET NEW.payment = 'Paid';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_payment_status_return` BEFORE UPDATE ON `sale_return` FOR EACH ROW BEGIN
    IF NEW.status = 'Complete' THEN
        SET NEW.payment = 'Paid';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `phone`, `image`, `email`, `type`, `country`, `city`, `district`, `address`, `zipcode`, `description`, `code`, `created_at`, `updated_at`) VALUES
(23, 'Công ty 2', '0976887887', '../upload/supplier/customer5.jpg', 'cskhhoangnhan23@gmail.com', 'Choose Type', 'Aland Islands', 'Select State', 'Choose District', '23232', '3423232', '232323', 'SP005', NULL, '2023-09-02 14:36:05');

--
-- Bẫy `supplier`
--
DELIMITER $$
CREATE TRIGGER `trg_before_insert_supplier_reference` BEFORE INSERT ON `supplier` FOR EACH ROW BEGIN
    DECLARE new_code VARCHAR(10);
    SET new_code = CONCAT('SP', LPAD((SELECT COUNT(*) + 1 FROM customer), 3, '0'));
    SET NEW.code = new_code;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trash_image_product`
--

CREATE TABLE `trash_image_product` (
  `id` int(20) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `product_trash_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `trash_image_product`
--

INSERT INTO `trash_image_product` (`id`, `image`, `product_trash_id`) VALUES
(59, 'C:/xampp/htdocs/admin-page/trash/661c0b1be03be.jpg', 103),
(60, 'C:/xampp/htdocs/admin-page/trash/661c0b954b542.jpg', 104),
(61, 'C:/xampp/htdocs/admin-page/trash/661c0c41c0fff.jpg', 105),
(62, 'C:/xampp/htdocs/admin-page/trash/661c0d51e1391.jpg', 106);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trash_product`
--

CREATE TABLE `trash_product` (
  `id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `minium_quantity` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `categoryproduct_id` int(11) DEFAULT NULL,
  `subcategoryproduct_id` int(11) DEFAULT NULL,
  `slug` varchar(1000) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `type_product` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_customer`
--

CREATE TABLE `user_customer` (
  `id` bigint(20) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_customer`
--

INSERT INTO `user_customer` (`id`, `customer_id`, `first_name`, `last_name`, `password`, `status`, `email`, `created_at`, `updated_at`) VALUES
(1, 11, 'Quốc', 'Hoàng Xuân', 'Hoangquoc318', 1, '2156210125@gmail.com', NULL, '2023-08-25 02:05:06'),
(4, NULL, 'Quốc', 'Hoàng Xuân', '$2y$10$djFGkT9qULlomGPpQxTRAOiPfW/IzjI2P7LDO.sYSDLxAPegrbsoK', 1, '216210125@gmail.com', NULL, '2023-08-25 02:05:06'),
(6, NULL, 'Quốc', 'Hoàng Xuân', '$2y$10$x7WPVPEiG62KUKNAC.w/Vuu0.mWoxvb1hAh0fFtPOyBGjjHG.LxC.', 1, '2156210000@gmail.com', NULL, '2024-04-06 05:32:06'),
(7, NULL, 'Nguyễn ', 'Thành Công', '$2y$10$l6VLKK6Vno9ekKgTeP4M/e0OjR7g0lALXUVTa6ts05Uw8.Kna/Zmi', 1, '1907htng@gmail.com', NULL, '2024-04-06 06:26:41'),
(8, NULL, 'Hoàng Xuân', 'Thành', '$2y$10$aPrjfh1dNbrBEX3JwKj8FuWRBCuqMxXjYZaH7EGZwCAwIJiK729cO', 1, '2156210166@gmail.com', NULL, '2024-04-06 08:29:52'),
(9, NULL, 'Hoàng Xuân Thành', 'nhân', '$2y$10$nNCciYbOcw66g1WfKjLP0eJMVoeDEMEwSQUmPGhJPCaAoiDCwJLoC', 1, '2156210167@gmail.com', NULL, '2024-04-06 08:32:17'),
(10, NULL, 'Nguyễn THành', 'Tân', '$2y$10$BFPbhwyQvcLPVGWTjHLnRemv/6co38Z5QhrMeLZG8QfwJpDp77aiu', 1, '2156210100@gmail.com', NULL, '2024-04-08 08:31:46'),
(11, NULL, 'Nguyen Thanh', 'Hoang', '$2y$10$3hcBc2A2iDEpJbtVOlUgmOSBN/VQNG3HuTvxyXXx7zVxdBvvQsHJ2', 1, '215621086@gmail.com', NULL, '2024-04-13 08:31:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_manager`
--

CREATE TABLE `user_manager` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_manager`
--

INSERT INTO `user_manager` (`id`, `firstname`, `lastname`, `email`, `phone`, `password`, `role`, `status`, `image`, `created_at`) VALUES
(9, 'Hoang', 'Xuanquoc', 'hxqduccopny@gmail.com', '0393643357', '$2y$10$W3QKuJRr7DhIuS3fG.sd2uX.o9qkAppTK0y8NcKw3MFCRwAuNWdVC', 'Manager', 'Active', '../upload/usermanager/6501fb0581ebb_Screenshot 2023-0.png', '2023-09-12 03:45:10'),
(10, 'Nguyen', 'Nhan', 'hoangnhan@hcmussh.edu.vn', '0926623782', '$2y$10$cnJRIaD2tiwMwkYGZytVsesAmTYxxJehuOiWdMiHDSs8IUMKBvGfa', 'Admin', 'Active', '../upload/usermanager/avatar-13.jpg', '2023-09-14 16:20:19'),
(11, 'Tran ', 'Dai', 'trandai@hcmussh.edu.vn', '02365458125', '$2y$10$uZ.aCWafWte0BCdnCiNO8eujah5iCYWXQANny0VfV/W4RSE./Z6fO', 'Salesman', 'Active', '../upload/usermanager/avatar-17.jpg', '2023-09-14 16:21:11');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Chỉ mục cho bảng `categoryproduct`
--
ALTER TABLE `categoryproduct`
  ADD PRIMARY KEY (`categoryproduct_id`);

--
-- Chỉ mục cho bảng `category_blog`
--
ALTER TABLE `category_blog`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `category_expense`
--
ALTER TABLE `category_expense`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `category_sub`
--
ALTER TABLE `category_sub`
  ADD PRIMARY KEY (`category_sub_id`);

--
-- Chỉ mục cho bảng `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`discount_id`);

--
-- Chỉ mục cho bảng `email_setting`
--
ALTER TABLE `email_setting`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `general_setting`
--
ALTER TABLE `general_setting`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `image_product`
--
ALTER TABLE `image_product`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payment_detail`
--
ALTER TABLE `payment_detail`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payment_return_detail`
--
ALTER TABLE `payment_return_detail`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`quotation_id`);

--
-- Chỉ mục cho bảng `quotation_detail`
--
ALTER TABLE `quotation_detail`
  ADD PRIMARY KEY (`quotation_id`,`product_id`);

--
-- Chỉ mục cho bảng `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`sale_id`);

--
-- Chỉ mục cho bảng `sale_detail`
--
ALTER TABLE `sale_detail`
  ADD PRIMARY KEY (`product_id`,`sale_id`);

--
-- Chỉ mục cho bảng `sale_return`
--
ALTER TABLE `sale_return`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `trash_image_product`
--
ALTER TABLE `trash_image_product`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `trash_product`
--
ALTER TABLE `trash_product`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user_customer`
--
ALTER TABLE `user_customer`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user_manager`
--
ALTER TABLE `user_manager`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT cho bảng `categoryproduct`
--
ALTER TABLE `categoryproduct`
  MODIFY `categoryproduct_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `category_blog`
--
ALTER TABLE `category_blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `category_expense`
--
ALTER TABLE `category_expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `category_sub`
--
ALTER TABLE `category_sub`
  MODIFY `category_sub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `discount`
--
ALTER TABLE `discount`
  MODIFY `discount_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `email_setting`
--
ALTER TABLE `email_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `expense`
--
ALTER TABLE `expense`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `general_setting`
--
ALTER TABLE `general_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `image_product`
--
ALTER TABLE `image_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT cho bảng `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `payment_detail`
--
ALTER TABLE `payment_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT cho bảng `payment_return_detail`
--
ALTER TABLE `payment_return_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT cho bảng `quotation`
--
ALTER TABLE `quotation`
  MODIFY `quotation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `sale`
--
ALTER TABLE `sale`
  MODIFY `sale_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT cho bảng `sale_return`
--
ALTER TABLE `sale_return`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT cho bảng `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `trash_image_product`
--
ALTER TABLE `trash_image_product`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT cho bảng `trash_product`
--
ALTER TABLE `trash_product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT cho bảng `user_customer`
--
ALTER TABLE `user_customer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `user_manager`
--
ALTER TABLE `user_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `sale_detail`
--
ALTER TABLE `sale_detail`
  ADD CONSTRAINT `sale_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
