-- Table to store links
-- DROP TABLE IF EXISTS link;
CREATE TABLE link (

    -- Primary Key
    id INT(11) AUTO_INCREMENT NOT NULL,

    -- Full Original URL
    url_full TEXT NULL,

    -- Indexable part of original URL
    url_index VARCHAR(255) NOT NULL DEFAULT "",

    -- Slug - shortened URL identifier
    slug VARCHAR(10) NULL CHARACTER SET utf8 COLLATE utf8_bin,

    -- Description - for search, etc.
    description TEXT NULL,

    -- Type - type of link - redirect, serve, multilink, iframe, reserved
    type VARCHAR(50) NOT NULL DEFAULT "redirect",

    -- Primary Key
    PRIMARY KEY (id),
    
    -- Indexes
    UNIQUE INDEX (slug),
    INDEX (type),
    INDEX (url_index)

)
-- Start high enough to reserver 3-char URLs for special cases
-- 62 ^ 3
AUTO_INCREMENT=238327;
