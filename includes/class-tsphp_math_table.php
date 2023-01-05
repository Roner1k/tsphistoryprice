<?php

class Tsphp_math_table
{
    public $tsphp_gen_calculation_table;

    public $tsphp_tmp_aggressive_calculation_table;
    public $tsphp_tmp_conservative_calculation_table;

    public $tsphp_aggressive_table;
    public $tsphp_aggressive_table_exist;

    public $tsphp_aggressive_alerts_table;
    public $tsphp_aggressive_alerts_table_exist;

    public $tsphp_conservative_table;
    public $tsphp_conservative_table_exist;

    public $tsphp_conservative_alerts_table;
    public $tsphp_conservative_alerts_table_exist;


    public function __construct()
    {
        global $wpdb;
        $this->tsphp_gen_calculation_table = $wpdb->prefix . 'gen_calculation_table';
        $this->tsphp_tmp_aggressive_calculation_table = $wpdb->prefix . 'tmp_aggressive_calculation_table';
        $this->tsphp_tmp_conservative_calculation_table = $wpdb->prefix . 'tmp_conservative_calculation_table';

        $this->tsphp_aggressive_table = $wpdb->prefix . 'aggressive_table';
        $this->tsphp_aggressive_alerts_table = $wpdb->prefix . 'aggressive_alerts_table';

        $this->tsphp_conservative_table = $wpdb->prefix . 'conservative_table';
        $this->tsphp_conservative_alerts_table = $wpdb->prefix . 'conservative_alerts_table';

        $tsphp_service = new Tsphp_wrapper_admin();
        $this->tsphp_aggressive_table_exist = $tsphp_service->tsphp_check_tables_exist($this->tsphp_aggressive_table);
        $this->tsphp_aggressive_alerts_table_exist = $tsphp_service->tsphp_check_tables_exist($this->tsphp_aggressive_alerts_table);
        $this->tsphp_conservative_table_exist = $tsphp_service->tsphp_check_tables_exist($this->tsphp_conservative_table);
        $this->tsphp_conservative_alerts_table_exist = $tsphp_service->tsphp_check_tables_exist($this->tsphp_conservative_alerts_table);

    }

//create and fill from imported vals
    public function tsphp_create_math_table($t_name)
    {
        global $wpdb;
        $tsphp_price_table_name = new Tsphp_import_build();

        $wpdb->query("DROP TABLE IF EXISTS $t_name");
//        var_dump("Recalc. Dropped and create new + $t_name");

        $SQL = 'CREATE TABLE IF NOT EXISTS `' . $t_name . '` (
          `dr_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `trade_date` varchar(255) NOT NULL,
          `alert_date` varchar(255) NOT NULL DEFAULT "0",				 
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
          `total_val` varchar(255) NOT NULL DEFAULT "100",
          `g_f_prc` varchar(255) NOT NULL,
          `f_f_prc` varchar(255) NOT NULL,
          `c_f_prc` varchar(255) NOT NULL,
          `s_f_prc` varchar(255) NOT NULL,
          `i_f_prc` varchar(255) NOT NULL,
          PRIMARY KEY (`dr_ID`)
        )';

        //add imported values
        $SQL_INSERT = "INSERT INTO $t_name (`trade_date`,`g_f_prc`,`f_f_prc`,`c_f_prc`,`s_f_prc`,`i_f_prc`) SELECT `date`,`g_fund`,`f_fund`,`c_fund`,`s_fund`,`i_fund` FROM $tsphp_price_table_name->tsphp_price_table";

        $SQL_UPDATE = "UPDATE $t_name SET
g_f_prc = g_f_prc,
f_f_prc = f_f_prc,
c_f_prc = c_f_prc,
s_f_prc = s_f_prc,
i_f_prc = i_f_prc";

//        g_f_prc = ROUND(g_f_prc,2),
//        f_f_prc= ROUND(f_f_prc,2),
//        c_f_prc = ROUND(c_f_prc,2),
//        s_f_prc = ROUND(s_f_prc,2),
//        i_f_prc = ROUND(i_f_prc,2)";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($SQL);
        dbDelta($SQL_INSERT);
        dbDelta($SQL_UPDATE);

    }

    public function tsphp_create_alerts_table($t_name)
    {
        $SQL = 'CREATE TABLE IF NOT EXISTS `' . $t_name . '` (
          `alert_date` varchar(255) NOT NULL,	
          `trade_date` varchar(255) NOT NULL,				 			 
          `g_f_per` varchar(255) NOT NULL DEFAULT "0",
          `f_f_per` varchar(255) NOT NULL DEFAULT "0",
          `c_f_per` varchar(255) NOT NULL DEFAULT "0",
          `s_f_per` varchar(255) NOT NULL DEFAULT "0",
          `i_f_per` varchar(255) NOT NULL DEFAULT "0",				
          `alert_reason` longtext,
          `alert_post_link` longtext,				  				
          PRIMARY KEY (`alert_date`)
        )';
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($SQL);
    }

//create tables on plugin first run
    public function tsphp_create_aggr_conservative_tables()
    {
        $this->tsphp_create_math_table($this->tsphp_aggressive_table);
        $this->tsphp_create_alerts_table($this->tsphp_aggressive_alerts_table);

        $this->tsphp_create_math_table($this->tsphp_conservative_table);
        $this->tsphp_create_alerts_table($this->tsphp_conservative_alerts_table);
    }


    /*
    * stage1
    *
    * G Fund Shares = (G Fund Perc * Total value)/ G Fund Price
    * G Fund Value = G Fund Shares * G Fund Price
    *
    * stage2
    * Total Value += & Fund Value
    *
    * */

    public function tsphp_conc_row($in_arr)
    {
        $output_str = '';
        $val_string = "(";
        foreach ($in_arr as $k => $r_data) {
            end($in_arr);
            $val_string .= '"' . $r_data . ($k === key($in_arr) ? '"' : '", ');
        }

        $val_string .= "),";
        $output_str .= $val_string;
        return $output_str;
    }

    /*
     * calc func
     * */

//multiple calc sum
    public static function tsp_bcsum(array $numbers)
    {
        $total = "0";
        foreach ($numbers as $number) {
            $total = bcadd($total, $number, 15);
        }
        return $total;
    }

//Excel-like ROUNDUP function:

    public static function round_up($value, $places)
    {
        $mult = pow(10, abs($places));
        return $places < 0 ?
            ceil($value / $mult) * $mult :
            ceil($value * $mult) / $mult;
    }

//calc
    public function tsphp_calc_math_table($upd_table, $source_table, $tmp_table)
    {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $this->tsphp_create_math_table($upd_table);

        /*
         * Create temp table
         * */
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS $tmp_table");

        $existing_columns = $wpdb->get_col("DESC {$upd_table}", 0);
        $SQL_CALCULATED = "CREATE TABLE IF NOT EXISTS $tmp_table (";
        foreach ($existing_columns as $k => $col) {
            $SQL_CALCULATED .= "`{$col}` varchar(255) NOT NULL, ";
        }
        $SQL_CALCULATED .= "PRIMARY KEY (`trade_date`)
)";
        dbDelta($SQL_CALCULATED);

        // delete trade dates that happened before the first allocation
        $first_td_row = "SELECT * FROM $source_table ORDER BY `alert_date` ASC LIMIT 1";
        $start_alloc = $wpdb->get_results($first_td_row);
        $first_td = $start_alloc[0]->trade_date;

        $f_alloc_r = "SELECT * FROM $upd_table WHERE trade_date = '$first_td'";
        $f_alloc_res = $wpdb->get_results($f_alloc_r);
        $st_ID = $f_alloc_res[0]->dr_ID;

        $wpdb->query("DELETE FROM $upd_table WHERE dr_ID < '$st_ID'");

        // delete from exampleTable where id >= 40000 and id <= 50000


        $SQL_MERGE = "UPDATE $upd_table INNER JOIN $source_table ON $upd_table.trade_date = $source_table.trade_date 
SET $upd_table.alert_date = $source_table.alert_date,
    $upd_table.g_f_per = $source_table.g_f_per,
    $upd_table.f_f_per = $source_table.f_f_per,
    $upd_table.c_f_per = $source_table.c_f_per,
    $upd_table.s_f_per = $source_table.s_f_per,
    $upd_table.i_f_per = $source_table.i_f_per;";

        dbDelta($SQL_MERGE);

//        var_dump($SQL_MERGE);

        $get_calc_query = "SELECT * FROM $upd_table";
        $calc_query = $wpdb->get_results($get_calc_query);


        //calculation process

        $upd_col = '';
        foreach ($existing_columns as $col) {
            if (!next($existing_columns)) {
                $upd_col .= $col . " = VALUES(" . $col . ")";
            } else {
                $upd_col .= $col . " = VALUES(" . $col . "),";
            }
        }

        $upd_val_string = '';
        $upd_table_str = '';

        $columns = implode(", ", $existing_columns);

        $tmp_sum = 100;
        $tmp_g_f_shr = 1;
        $tmp_f_f_shr = 1;
        $tmp_c_f_shr = 1;
        $tmp_s_f_shr = 1;
        $tmp_i_f_shr = 1;

        $tmp_g_f_per = 100;
        $tmp_f_f_per = 0;
        $tmp_c_f_per = 0;
        $tmp_s_f_per = 0;
        $tmp_i_f_per = 0;

        foreach ($calc_query as $key => $row) {

            $row->g_f_shr = $tmp_g_f_shr;
            $row->f_f_shr = $tmp_f_f_shr;
            $row->c_f_shr = $tmp_c_f_shr;
            $row->s_f_shr = $tmp_s_f_shr;
            $row->i_f_shr = $tmp_i_f_shr;

            $row->g_f_val = bcmul($row->g_f_shr, $row->g_f_prc, 16);
            $row->f_f_val = $this->round_up(bcmul($row->f_f_shr, $row->f_f_prc, 15), 14);
            $row->c_f_val = $this->round_up(bcmul($row->c_f_shr, $row->c_f_prc, 15), 14);
            $row->s_f_val = $this->round_up(bcmul($row->s_f_shr, $row->s_f_prc, 15), 14);
            $row->i_f_val = $this->round_up(bcmul($row->i_f_shr, $row->i_f_prc, 15), 14);


//            $row->g_f_val = $row->g_f_shr * $row->g_f_prc;
//            $row->f_f_val = $row->f_f_shr * $row->f_f_prc;
//            $row->c_f_val = $row->c_f_shr * $row->c_f_prc;
//            $row->s_f_val = $row->s_f_shr * $row->s_f_prc;
//            $row->i_f_val = $row->i_f_shr * $row->i_f_prc;

            $sum_arr = array($row->g_f_val, $row->f_f_val, $row->c_f_val, $row->s_f_val, $row->i_f_val);
            $row->total_val = $this->tsp_bcsum($sum_arr);
//            var_dump( $row->total_val );
            $tmp_sum = $row->total_val;

            //start  from 100$ total value  like in excel
            if ($row->trade_date == $first_td) $tmp_sum = 100;

            if (strlen($row->alert_date) > 1) {
                $tmp_g_f_per = $row->g_f_per;
                $tmp_f_f_per = $row->f_f_per;
                $tmp_c_f_per = $row->c_f_per;
                $tmp_s_f_per = $row->s_f_per;
                $tmp_i_f_per = $row->i_f_per;
            }
//            $row->g_f_per = round($row->g_f_val / $tmp_sum, 2) * 100;
//            $row->f_f_per = round($row->f_f_val / $tmp_sum, 2) * 100;
//            $row->c_f_per = round($row->c_f_val / $tmp_sum, 2) * 100;
//            $row->s_f_per = round($row->s_f_val / $tmp_sum, 2) * 100;
//            $row->i_f_per = round($row->i_f_val / $tmp_sum, 2) * 100;

            $row->g_f_per = round($row->g_f_val / $tmp_sum, 2) * 100;
            $row->f_f_per = round($row->f_f_val / $tmp_sum, 2) * 100;
            $row->c_f_per = round($row->c_f_val / $tmp_sum, 2) * 100;
            $row->s_f_per = round($row->s_f_val / $tmp_sum, 2) * 100;
            $row->i_f_per = round($row->i_f_val / $tmp_sum, 2) * 100;

            $upd_val_string .= $this->tsphp_conc_row($row);

            if (strlen($row->alert_date) > 1) {
                $upd_val_string .= $this->tsphp_conc_row($row);

                $row->total_val = $tmp_sum;

                $row->g_f_per = $tmp_g_f_per;
                $row->f_f_per = $tmp_f_f_per;
                $row->c_f_per = $tmp_c_f_per;
                $row->s_f_per = $tmp_s_f_per;
                $row->i_f_per = $tmp_i_f_per;

//                $row->g_f_shr = round(((($row->g_f_per / 100) * $row->total_val) / $row->g_f_prc), 15);
//                $row->f_f_shr = round(((($row->f_f_per / 100) * $row->total_val) / $row->f_f_prc), 15);
//                $row->c_f_shr = round(((($row->c_f_per / 100) * $row->total_val) / $row->c_f_prc), 15);
//                $row->s_f_shr = round(((($row->s_f_per / 100) * $row->total_val) / $row->s_f_prc), 15);
//                $row->i_f_shr = round(((($row->i_f_per / 100) * $row->total_val) / $row->i_f_prc), 15);

                $_g_f_per = $row->g_f_per / 100;
                $_f_f_per = $row->f_f_per / 100;
                $_c_f_per = $row->c_f_per / 100;
                $_s_f_per = $row->s_f_per / 100;
                $_i_f_per = $row->i_f_per / 100;

                $row->g_f_shr = $this->round_up(bcdiv(bcmul(($_g_f_per), $row->total_val, 15), $row->g_f_prc, 15), 14);
                $row->f_f_shr = $this->round_up(bcdiv(bcmul(($_f_f_per), $row->total_val, 15), $row->f_f_prc, 15), 14);
                $row->c_f_shr = $this->round_up(bcdiv(bcmul(($_c_f_per), $row->total_val, 15), $row->c_f_prc, 15), 14);
                $row->s_f_shr = $this->round_up(bcdiv(bcmul(($_s_f_per), $row->total_val, 15), $row->s_f_prc, 15), 14);
                $row->i_f_shr = $this->round_up(bcdiv(bcmul(($_i_f_per), $row->total_val, 15), $row->i_f_prc, 15), 14);

                $tmp_g_f_shr = $row->g_f_shr;
                $tmp_f_f_shr = $row->f_f_shr;
                $tmp_c_f_shr = $row->c_f_shr;
                $tmp_s_f_shr = $row->s_f_shr;
                $tmp_i_f_shr = $row->i_f_shr;

//                $row->g_f_val = round(($row->g_f_shr * $row->g_f_prc), 2);
//                $row->f_f_val = round(($row->f_f_shr * $row->f_f_prc), 2);
//                $row->c_f_val = round(($row->c_f_shr * $row->c_f_prc), 2);
//                $row->s_f_val = round(($row->s_f_shr * $row->s_f_prc), 2);
//                $row->i_f_val = round(($row->i_f_shr * $row->i_f_prc), 2);

                $row->g_f_val = $this->round_up(bcmul($row->g_f_shr, $row->g_f_prc, 15), 14);
                $row->f_f_val = $this->round_up(bcmul($row->f_f_shr, $row->f_f_prc, 15), 14);
                $row->c_f_val = $this->round_up(bcmul($row->c_f_shr, $row->c_f_prc, 15), 14);
                $row->s_f_val = $this->round_up(bcmul($row->s_f_shr, $row->s_f_prc, 15), 14);
                $row->i_f_val = $this->round_up(bcmul($row->i_f_shr, $row->i_f_prc, 15), 14);

                //change order
                $upd_table_str .= $this->tsphp_conc_row($row);
                $row->trade_date .= 'T00:00';
                $upd_val_string .= $this->tsphp_conc_row($row);
            }
//            array_walk($row, fn(&$x) => $x = "'$x'");
//            $vals = implode(", ", (array)$row);

        }
//        print_r($upd_val_string);
        $values = substr_replace($upd_val_string, ' ', -1);
        $upd_values = substr_replace($upd_table_str, ' ', -1);
//        var_dump($upd_values);

        $sql_upd_tmp = "INSERT INTO $tmp_table ($columns) VALUES $values ON DUPLICATE KEY UPDATE $upd_col;";
        dbDelta($sql_upd_tmp);

        $SQL_UPD_MAIN = "UPDATE $upd_table INNER JOIN $tmp_table ON $upd_table.trade_date = $tmp_table.trade_date 
SET $upd_table.alert_date = $tmp_table.alert_date,
      $upd_table.g_f_per = $tmp_table.g_f_per,
    $upd_table.f_f_per = $tmp_table.f_f_per,
    $upd_table.c_f_per = $tmp_table.c_f_per,
    $upd_table.s_f_per = $tmp_table.s_f_per,
    $upd_table.i_f_per = $tmp_table.i_f_per,  
    
    $upd_table.g_f_shr = $tmp_table.g_f_shr,
    $upd_table.f_f_shr = $tmp_table.f_f_shr,
    $upd_table.c_f_shr = $tmp_table.c_f_shr,
    $upd_table.s_f_shr = $tmp_table.s_f_shr,
    $upd_table.i_f_shr = $tmp_table.i_f_shr,            
    $upd_table.g_f_val = $tmp_table.g_f_val,
    $upd_table.f_f_val = $tmp_table.f_f_val,
    $upd_table.c_f_val = $tmp_table.c_f_val,
    $upd_table.s_f_val = $tmp_table.s_f_val,
    $upd_table.g_f_val = $tmp_table.g_f_val,
    $upd_table.total_val = $tmp_table.total_val;";

//        dbDelta($SQL_UPD);
        dbDelta($SQL_UPD_MAIN);

        //update main table alert rows which uses for shortcodes data
        $sql_upd_row = "INSERT INTO $upd_table ($columns) VALUES $upd_values ON DUPLICATE KEY UPDATE $upd_col;";
        dbDelta($sql_upd_row);


    }

//run recalc and create new tables from existing alert data and import
    public function tsphp_calc_math_run()
    {
        $this->tsphp_calc_math_table($this->tsphp_aggressive_table, $this->tsphp_aggressive_alerts_table, $this->tsphp_tmp_aggressive_calculation_table);
        $this->tsphp_calc_math_table($this->tsphp_conservative_table, $this->tsphp_conservative_alerts_table, $this->tsphp_tmp_conservative_calculation_table);
    }


}