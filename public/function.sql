DELIMITER //

CREATE FUNCTION LEVENSHTEIN(s1 VARCHAR(255), s2 VARCHAR(255))
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE s1_len, s2_len, i, j, c, c_temp INT;
    DECLARE cost INT;
    DECLARE s1_char CHAR;
    DECLARE cv0, cv1 VARBINARY(256);
    
    SET s1_len = CHAR_LENGTH(s1);
    SET s2_len = CHAR_LENGTH(s2);
    IF s1_len = 0 THEN
        RETURN s2_len;
    END IF;
    IF s2_len = 0 THEN
        RETURN s1_len;
    END IF;
    
    SET cv1 = 0x00;
    SET j = 1;
    WHILE j <= s2_len DO
        SET cv1 = CONCAT(cv1, UNHEX(HEX(j)));
        SET j = j + 1;
    END WHILE;
    
    SET i = 1;
    WHILE i <= s1_len DO
        SET s1_char = SUBSTRING(s1, i, 1);
        SET c = i;
        SET cv0 = UNHEX(HEX(i));
        SET j = 1;
        WHILE j <= s2_len DO
            SET c_temp = c;
            SET c = CONV(HEX(SUBSTRING(cv1, j, 1)), 16, 10);
            IF s1_char = SUBSTRING(s2, j, 1) THEN
                SET cost = 0;
            ELSE
                SET cost = 1;
            END IF;
            SET cv0 = CONCAT(cv0, UNHEX(HEX(LEAST(c + 1, c_temp + 1, c + cost))));
            SET j = j + 1;
        END WHILE;
        SET cv1 = cv0;
        SET i = i + 1;
    END WHILE;
    RETURN CONV(HEX(SUBSTRING(cv1, s2_len, 1)), 16, 10);
END;
//

DELIMITER ;