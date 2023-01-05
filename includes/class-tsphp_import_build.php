<?php

class Tsphp_import_build
{
    public $tsphp_price_table;


    public function __construct()
    {
        global $wpdb;
        $this->tsphp_price_table = $wpdb->prefix . 'share_price_history';

    }

    //parse csv on separate rows
    public function tsphp_parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
    {
        $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
        $enc = preg_replace_callback(
            '/"(.*?)"/s',
            function ($field) {
                return urlencode(utf8_encode($field[1]));
            },
            $enc
        );
        $lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
        return array_map(
            function ($line) use ($delimiter, $trim_fields) {
                $fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
                return array_map(
                    function ($field) {

                        return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                    },
                    $fields
                );
            },
            $lines
        );
    }

    //old req
    public function tsphp_request_data($start_date, $end_date)
    {
        $tsphp_csv = file_get_contents("https://secure.tsp.gov/components/CORS/getSharePricesRaw.html?startdate=$start_date&enddate=$end_date&Lfunds=0&InvFunds=1");
        $tsphp_parsed_data = $this->tsphp_parse_csv($tsphp_csv);

        //filter empty lines after pars
        return array_filter($tsphp_parsed_data, function ($v) {
            if (count($v) > 1) {
                return $v;
            }
        });
    }

    //del useless cols func
    public function array_columns_delete(&$array, $keys, $recursive = false)
    {
        return array_walk($array, function (&$v) use ($keys, $recursive) {
            foreach ((array)$keys as $key) {
                if (isset($v[$key])) unset($v[$key]);
                if ($recursive == true) {
                    foreach ($v as $k => &$s) {
                        if (is_array($s)) array_columns_delete($s, $keys, $recursive);
                    }
                }
            }
        });
    }


    public function tsphp_new_imp()
    {
        $log_class = new Tsphp_log_build;

        $context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36"
                )
            )
        );

//        https://www.tsp.gov/data/getSharePricesRaw_startdate_20220424_enddate_20220524_Lfunds_1_InvFunds_1_download_0.html
//     2   https://www.tsp.gov/data/getSharePricesRaw_startdate_20220424_enddate_20220524_Lfunds_1_InvFunds_1_download_0.csv

        $url = 'https://www.tsp.gov/data/fund-price-history.csv';
//        $url = 'https://www.tsp.gov/fund-performance/share-price-history/';

        $loaded_data = file_get_contents($url, false, $context);
        $p_data = $this->tsphp_parse_csv($loaded_data);

        if (strpos($http_response_header[0], "200")) {
//            echo "SUCCESS";
            $log_class->tsphp_add_import_log("Import init. Response received from the link", 0);

            if ($p_data[0][0] !== "Date") {
                $err_text = substr($loaded_data, 0, 50);
                $log_class->tsphp_add_import_log("Parse error. Check the received data: $err_text", 1);

                return false;

            }

        } else {
//            echo "FAILED";
            $error = error_get_last();
            $log_class->tsphp_add_import_log("Import init failed. HTTP request failed. Error was: " . $error['message'], 1);

            return false;
        }

//        $p_data = $this->tsphp_parse_csv($loaded_data);

        $skip_col_index = array();


        //leave only the required columns
        foreach ($p_data[0] as $k => $data) {
            $data = strtolower(str_replace(' ', '_', $data));
            if ($data !== 'date' && $data !== 'g_fund' && $data !== 'f_fund' && $data !== 'c_fund' && $data !== 's_fund' && $data !== 'i_fund') {
                $skip_col_index[] = $k;
            }
        }

        $this->array_columns_delete($p_data, $skip_col_index, false);
        $del_old_dates = 0;
        foreach ($p_data as $k => $data) {
            if (count($data) < 2) unset($p_data[$k]);

            //del
//            if ($data[0] == '2006-05-30') $del_old_dates = $k + 1;

        }

//        $p_data = array_slice($p_data, 0, $del_old_dates);


//        echo '<pre>';
//        print_r($p_data);
//        echo '</pre>';

        return $p_data;

    }


    //DB func
    public function tsphp_install_DB($s_date, $e_date)
    {
        global $wpdb;
        //not available now
//        $tsphp_parsed_data_old = $this->tsphp_request_data($s_date, $e_date);

        $tsphp_parsed_data = $this->tsphp_new_imp();
        $log_class = new Tsphp_log_build;

        if ($tsphp_parsed_data) {
            $tsphp_header_row = current($tsphp_parsed_data);
            $tsphp_header_row = array_map(function ($v) {
                return str_replace(' ', '_', strtolower($v));
            }, $tsphp_header_row);

            unset($tsphp_parsed_data[0]);
            $tsphp_data_rows = array_values($tsphp_parsed_data);
            $tsphp_data_rows = array_map(function ($v) {

                $v[0] = "'" . date('Y-m-d', strtotime($v[0])) . "'";

                return implode(', ', $v);

            }, $tsphp_data_rows);

            $SQL = 'CREATE TABLE IF NOT EXISTS `' . $this->tsphp_price_table . '` ( ';

            foreach ($tsphp_header_row as $row) {
                $SQL .= "`{$row}` varchar(255) NOT NULL,";
            }

            $SQL .= 'PRIMARY KEY (`date`)
            )';

            $columns = implode(", ", $tsphp_header_row);
            // $escaped_values = array_map('mysqli_real_escape_string', $tsphp_data_rows);
            // $values = implode(", ", $tsphp_data_rows);


            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($SQL);

            unset($tsphp_header_row[0]);
            $upd_col = array_values($tsphp_header_row);
            $upd_val = '';

            foreach ($upd_col as $col) {
                if (!next($upd_col)) {
                    $upd_val .= $col . " = VALUES(" . $col . ")";
                } else {
                    $upd_val .= $col . " = VALUES(" . $col . "),";
                }
            }
            $count_before_log = $wpdb->get_var("SELECT COUNT(*) FROM $this->tsphp_price_table");

            foreach ($tsphp_data_rows as $key => $row) {
                $upd_row_val = '(' . $row . ')';
                $sql_upd_row = "INSERT INTO $this->tsphp_price_table ($columns) VALUES $upd_row_val ON DUPLICATE KEY UPDATE $upd_val";
//                var_dump($sql_upd_row);
                dbDelta($sql_upd_row);
            }

            $count_after_log = $wpdb->get_var("SELECT COUNT(*) FROM $this->tsphp_price_table");
            $count_log = $count_after_log - $count_before_log;


            $log_class->tsphp_add_import_log("Add $count_log new dates", 0);


        }
    }

    function tsphp_unistall_DB()
    {
        global $wpdb;
//        $sql = "DROP TABLE IF EXISTS $this->tsphp_price_table";
//        $wpdb->query($sql);
    }


//    cron auto imp
    function tsphp_auto_import()
    {
        $start_date = date('Y-m-d', strtotime(' - 1 months'));
        $end_date = date('Y-m-d');
        $this->tsphp_install_DB(str_replace('-', '', $start_date), str_replace('-', '', $end_date));

        //recalc after import
        $math_obj = new Tsphp_math_table();
        $math_obj->tsphp_calc_math_run();


    }


}
