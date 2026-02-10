CREATE TABLE pages (
    tx_vgwort_pixel varchar(50) DEFAULT '' NOT NULL,
    tx_vgwort_ignore tinyint(4) unsigned DEFAULT '0' NOT NULL,

    KEY vgwort_pixel (tx_vgwort_pixel)
);
