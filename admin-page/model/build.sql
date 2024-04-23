BEGIN
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
END