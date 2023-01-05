<?php

class Tsphp_log_build
{
    public $tsphp_import_log_tname;

    public function __construct()
    {
        global $wpdb;
        $this->tsphp_import_log_tname = $wpdb->prefix . 'history_price_import_log';

    }

//create log when activate pl
    public function tsphp_create_log_table()
    {
        global $wpdb;

//        $sql = "DROP TABLE IF EXISTS $this->tsphp_import_log_tname";

        $SQL = 'CREATE TABLE IF NOT EXISTS `' . $this->tsphp_import_log_tname . '` (
              `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `date` DATETIME  NOT NULL,	
              `log_text` longtext,			 
              `log_warning` varchar(255) NOT NULL DEFAULT "0",
              PRIMARY KEY (`ID`)
                            )';

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//        print_r($SQL);
        dbDelta($SQL);
    }

    public function tsphp_add_import_log($msg, $warn)
    {

        global $wpdb;
        $date = date('y-m-d H:i:s');

//        $this->dbh
        $es_msg = $wpdb->_real_escape($msg);
        $SQL = "INSERT INTO $this->tsphp_import_log_tname (date, log_text, log_warning) VALUES ('$date', '$es_msg', '$warn') ";
//        print_r($SQL);

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($SQL);

    }

}

