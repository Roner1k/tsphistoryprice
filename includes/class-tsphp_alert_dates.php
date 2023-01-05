<?php

class Tsphp_alert_dates
{
    public $tsphp_aggressive_table;
    public $tsphp_aggressive_table_exist;


    public function __construct()
    {
        global $wpdb;
        $this->tsphp_aggressive_table = $wpdb->prefix . 'aggressive_table';

        $table_name = $this->tsphp_aggressive_table;
        $the_query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
        $this->tsphp_aggressive_table_exist = $wpdb->get_var($the_query) == $table_name;

    }

    public function tsphp_create_math_table()
    {
        $tsphp_price_table_name = new Tsphp_import_build();

        $SQL = 'CREATE TABLE IF NOT EXISTS `' . $this->tsphp_aggressive_table . '` (
				  `dr_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `trade_date` varchar(255) NOT NULL,
				  `alert_date` varchar(255) NOT NULL,				 
				  `g_f_per` varchar(255) NOT NULL DEFAULT "0",
				  `f_f_per` varchar(255) NOT NULL DEFAULT "0",
				  `c_f_per` varchar(255) NOT NULL DEFAULT "0",
				  `s_f_per` varchar(255) NOT NULL DEFAULT "0",
				  `i_f_per` varchar(255) NOT NULL DEFAULT "0",
				  `g_f_shr` varchar(255) NOT NULL DEFAULT "0",
				  `f_f_shr` varchar(255) NOT NULL DEFAULT "0",
				  `c_f_shr` varchar(255) NOT NULL DEFAULT "0",
				  `s_f_shr` varchar(255) NOT NULL DEFAULT "0",
				  `i_f_shr` varchar(255) NOT NULL DEFAULT "0",
				  `g_f_val` varchar(255) NOT NULL DEFAULT "0",
				  `f_f_val` varchar(255) NOT NULL DEFAULT "0",
				  `c_f_val` varchar(255) NOT NULL DEFAULT "0",
				  `s_f_val` varchar(255) NOT NULL DEFAULT "0",
				  `i_f_val` varchar(255) NOT NULL DEFAULT "0",
				  `total_val` varchar(255) NOT NULL DEFAULT "0",
				  `g_f_prc` varchar(255) NOT NULL,
				  `f_f_prc` varchar(255) NOT NULL,
				  `c_f_prc` varchar(255) NOT NULL,
				  `s_f_prc` varchar(255) NOT NULL,
				  `i_f_prc` varchar(255) NOT NULL,				
				  PRIMARY KEY (`dr_ID`)
				)';
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


//        $SQL_UPDATE = 'UPDATE' . $this->tsphp_aggressive_table . 'SET g_f_prc = ( SELECT g_fund FROM' . $tsphp_price_table_name->tsphp_price_table . '
//        WHERE' . $tsphp_price_table_name->tsphp_price_table . id . '=' . $this->tsphp_aggressive_table . id . ')';
//        var_dump($SQL_UPDATE);

        $SQL_UPDATE = "INSERT INTO $this->tsphp_aggressive_table (`trade_date`,`g_f_prc`,`f_f_prc`,`c_f_prc`,`s_f_prc`,`i_f_prc`) SELECT `date`,`g_fund`,`f_fund`,`c_fund`,`s_fund`,`i_fund` FROM $tsphp_price_table_name->tsphp_price_table";

        dbDelta($SQL);
        dbDelta($SQL_UPDATE);
//        var_dump($SQL_UPDATE);

//        $SQL = 'CREATE TABLE IF NOT EXISTS `' . $this->tsphp_price_table . '` ( ';
//
//        foreach ($tsphp_header_row as $row) {
//            $SQL .= "`{$row}` varchar(255) NOT NULL,";
//        }
//
//        $SQL .= 'PRIMARY KEY (`date`)
//        )';
    }

}