<?php

class Tsphp_shortcodes
{
    public $tsphp_math_class;
    public $tsphp_import_class;

    public function __construct()
    {
        $this->tsphp_math_class = new Tsphp_math_table();
        $this->tsphp_import_class = new Tsphp_import_build();
    }

    /*
     * shortcode Alocation table
     */
    public function tsphp_alocation_table($alert_type, $if_exist, $attr)
    {
        $alert_name = '';
        if (strcmp($alert_type, 'tsp_aggressive_alerts_table') == 0) {
            $alert_name = 'Aggressive';
        } elseif (strcmp($alert_type, 'tsp_conservative_alerts_table') == 0) {
            $alert_name = 'Conservative';

        }

        global $wpdb;

        if ($if_exist) {
            $hist_url = '/';
            if (!empty($attr['historical_url'])) $hist_url = $attr['historical_url'];


            $existing_columns = $wpdb->get_col("DESC {$alert_type}", 0);
            $sql_cols = implode(', ', $existing_columns);

            $alocation_table_values = $wpdb->get_results("SELECT $sql_cols FROM " . $alert_type . " ORDER BY alert_date DESC LIMIT 2");

            include 'shortcode-tsphp_alocation_table.php';

        } else {
            return ' - No data - ';
        }

    }

    public function tsphp_alocation_aggr_table($sh_attr)
    {

        ob_start();

        $this->tsphp_alocation_table($this->tsphp_math_class->tsphp_aggressive_alerts_table, $this->tsphp_math_class->tsphp_aggressive_alerts_table_exist, $sh_attr);

        return ob_get_clean();

    }

    public function tsphp_alocation_cons_table($sh_attr)
    {
        ob_start();

        $this->tsphp_alocation_table($this->tsphp_math_class->tsphp_conservative_alerts_table, $this->tsphp_math_class->tsphp_conservative_alerts_table_exist, $sh_attr);

        return ob_get_clean();

    }

    /*
     *shortcode PAST Allocations
     */

//    public function tsp_create_fund_data($fund, $arr, $arr_k, $arr_i)
//    {
//        $fund_per = $fund . "_f_per";
//        $fund_dat = $fund . "_f_dat";
//        $tmp_arr = array();
////        print_r($fund_per);
//
//        if ($arr_i->alert_date && $arr_i->$fund_per >= 0) {
//            $tmp_arr = array('x' => strtotime($arr_i->alert_date) * 1000, 'y' => floatval($arr_i->$fund_per));
//            if ($arr_k !== 0)
//                if (array_key_exists($arr_k + 1, $arr)) {
//                    $tmp_arr = array('x' => (strtotime($arr[$arr_k]->alert_date) * 1000) + 10000, 'y' => floatval($arr[$arr_k + 1]->$fund_per));
//                }
//
//        }
//        return array($fund_dat => $tmp_arr);
//    }

    public function tsphp_past_alocations($alert_type, $if_exist, $calc_type)
    {
        $alert_name = '';
        if (strcmp($alert_type, 'tsp_aggressive_alerts_table') == 0) {
            $alert_name = 'Aggressive';
        } elseif (strcmp($alert_type, 'tsp_conservative_alerts_table') == 0) {
            $alert_name = 'Conservative';
        }

        global $wpdb;

        if ($if_exist) {


            /*graph query*/
            $past_allocations = $wpdb->get_results("SELECT alert_date, g_f_per, f_f_per, c_f_per, s_f_per, i_f_per  FROM " . $alert_type . " ORDER BY alert_date ASC");

            //stacked percents chart
            $g_fund_data = array();
            $f_fund_data = array();
            $c_fund_data = array();
            $s_fund_data = array();
            $i_fund_data = array();

            foreach ($past_allocations as $k => $alloc) {
//                if ($alloc->alert_date && $alloc->g_f_per >= 0) $g_fund_data['g_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->g_f_per));
//                if ($alloc->alert_date && $alloc->f_f_per >= 0) $f_fund_data['f_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->f_f_per));
//                if ($alloc->alert_date && $alloc->c_f_per >= 0) $c_fund_data['c_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->c_f_per));
//                if ($alloc->alert_date && $alloc->s_f_per >= 0) $s_fund_data['s_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->s_f_per));
//                if ($alloc->alert_date && $alloc->i_f_per >= 0) $i_fund_data['i_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->i_f_per));

                if ($alloc->alert_date && $alloc->g_f_per >= 0) {
                    $g_fund_data['g_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->g_f_per));
//                    if ($k !== 0)
                    if (array_key_exists($k + 1, $past_allocations)) {
                        $g_fund_data['g_f_dat'][] = array('x' => (strtotime($past_allocations[$k]->alert_date) * 1000) + 10000, 'y' => floatval($past_allocations[$k + 1]->g_f_per));
                    }
                }
                if ($alloc->alert_date && $alloc->f_f_per >= 0) {
                    $f_fund_data['f_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->f_f_per));
//                    if ($k !== 0)

                    if (array_key_exists($k + 1, $past_allocations)) {
                        $f_fund_data['f_f_dat'][] = array('x' => (strtotime($past_allocations[$k]->alert_date) * 1000) + 10000, 'y' => floatval($past_allocations[$k + 1]->f_f_per));
                    }
                }

                if ($alloc->alert_date && $alloc->c_f_per >= 0) {
                    $c_fund_data['c_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->c_f_per));
//                    if ($k !== 0)

                    if (array_key_exists($k + 1, $past_allocations)) {
                        $c_fund_data['c_f_dat'][] = array('x' => (strtotime($past_allocations[$k]->alert_date) * 1000) + 10000, 'y' => floatval($past_allocations[$k + 1]->c_f_per));
                    }
                }
                if ($alloc->alert_date && $alloc->s_f_per >= 0) {
                    $s_fund_data['s_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->s_f_per));
//                    if ($k !== 0)

                    if (array_key_exists($k + 1, $past_allocations)) {
                        $s_fund_data['s_f_dat'][] = array('x' => (strtotime($past_allocations[$k]->alert_date) * 1000) + 10000, 'y' => floatval($past_allocations[$k + 1]->s_f_per));
                    }
                }

                if ($alloc->alert_date && $alloc->i_f_per >= 0) {
                    $i_fund_data['i_f_dat'][] = array('x' => strtotime($alloc->alert_date) * 1000, 'y' => floatval($alloc->i_f_per));
//                    if ($k !== 0)

                    if (array_key_exists($k + 1, $past_allocations)) {
                        $i_fund_data['i_f_dat'][] = array('x' => (strtotime($past_allocations[$k]->alert_date) * 1000) + 10000, 'y' => floatval($past_allocations[$k + 1]->i_f_per));
                    }
                }

//                echo '<pre>';
//                print_r($i_fund_data);
//                echo '</pre>';

            }

            $all_graph_data = array_merge($g_fund_data, $f_fund_data, $c_fund_data, $s_fund_data, $i_fund_data);


            /*table query*/

            //paginate two shortcodes in one page - disabled
            $cpage_alert_type = 'cpage' . strtolower($alert_name);
            $alloc_items_per_page = 5;

            $alloc_page = isset($_GET[$cpage_alert_type]) ? abs((int)$_GET[$cpage_alert_type]) : 1;
            $alloc_offset = ($alloc_page * $alloc_items_per_page) - $alloc_items_per_page;

            $query = 'SELECT * FROM ' . $alert_type;

            $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
            $total = $wpdb->get_var($total_query);

            $alloc_results = $wpdb->get_results($query . ' ORDER BY alert_date DESC');

//            pagi disabled
//            $alloc_results = $wpdb->get_results($query . ' ORDER BY alert_date DESC LIMIT ' . $alloc_offset . ', ' . $alloc_items_per_page, OBJECT);

            //add performance values

            /*
             * New query +1 item to have opportunity calculate last row performance
             * */

            //            pagi disabled
//            $alloc_items_per_page_p1 = $alloc_items_per_page + 1;
//            $alloc_results_p1 = $wpdb->get_results($query . ' ORDER BY alert_date DESC LIMIT ' . $alloc_offset . ', ' . $alloc_items_per_page_p1, OBJECT);
//            $perform_results = $wpdb->get_results("SELECT trade_date, alert_date, total_val  FROM " . $calc_type . " ORDER BY alert_date ASC");

//           $alloc_items_per_page_p1 = $alloc_items_per_page + 1;
            $perform_results = $wpdb->get_results("SELECT trade_date, alert_date, total_val  FROM " . $calc_type . " ORDER BY alert_date ASC");

            foreach ($alloc_results as $al_row) {
                foreach ($perform_results as $per_row) {
                    $al_row->total_val = 0;
                    if ($al_row->trade_date == $per_row->trade_date) {
                        $al_row->total_val = $per_row->total_val;
                        break;
                    }
                }

            }
            $alloc_res_tmp = $alloc_results;

            //calc and insert to main query for table
            foreach ($alloc_results as $al_k => $al_row) {
                $next_k = $al_k + 1;
                $cur_val = floor($alloc_res_tmp[$al_k]->total_val * 10000) / 10000;

//                foreach ($alloc_results_p1 as $k_p1 => $row_p1) {
//                    if ($al_row->trade_date == $row_p1->trade_date) {

                if (array_key_exists($next_k, $alloc_res_tmp)) {
                    $next_val = floor($alloc_res_tmp[$next_k]->total_val * 10000) / 10000;
                    if ($alloc_res_tmp[$al_k]->total_val == 0) $next_val = 0;

                    $al_row->total_val = $this->tsphp_perf_per($cur_val, $next_val, 1);
//                    echo "<pre>";
//                    print_r($cur_val);
//                    echo "<br>";
//                    print_r($next_val);
//                    echo "</pre>";

                } else {
                    $al_row->total_val = $this->tsphp_perf_per($cur_val, 100, 1);
                }

//                    }
//
//                }

            }

            include 'shortcode-tsphp_past_alocations.php';

        } else {
            return ' - No data - ';
        }

//        return ob_get_clean();
    }

    public function tsphp_past_alocations_aggr()
    {
        ob_start();

        $this->tsphp_past_alocations($this->tsphp_math_class->tsphp_aggressive_alerts_table, $this->tsphp_math_class->tsphp_aggressive_alerts_table_exist, $this->tsphp_math_class->tsphp_aggressive_table);

        return ob_get_clean();

    }

    public function tsphp_past_alocations_cons()
    {
        ob_start();

        $this->tsphp_past_alocations($this->tsphp_math_class->tsphp_conservative_alerts_table, $this->tsphp_math_class->tsphp_conservative_alerts_table_exist, $this->tsphp_math_class->tsphp_conservative_table);

        return ob_get_clean();

    }


    /*
     * shortcode  Performance
     */
    public function tsphp_closest($dates, $findate, $next)
    {
        //search closest date, if end\start date not exist
        $newDates = array();

        foreach ($dates as $date) {
            $newDates[] = $date[0];
        }
        if ($next)
            sort($newDates);
        else
            arsort($newDates);

        $res = array();
        foreach ($newDates as $a) {
            if ($a >= $findate && $next) {
                $res = $a;
                break;
            } elseif ($a <= $findate && !$next) {
                $res = $a;
                break;
            }
        }
        foreach ($dates as $date) {
            if ($date[0] == $res) {
                /*
               var_dump(date('Y-m-d', $date[0]));
               echo '<br>';
               print_r( $date[1]);
               echo '<hr>';
                */
                return $date[1];
            }
        }

    }


    /* Get YTD
     * (YTD) return on a portfolio, subtract the starting value from the current value and divide it by the starting value.
     *  Multiply by 100 to convert this figure into a percentage.
     *
     */
    public function tsphp_perf_per($new, $old, $precision)
    {
        //calc performance value
        if ($old == 0) return "<span class='arr-up holding'>Pending</span>";

        $res = (($new - $old) / $old) * 100;
        if ($res > 0) {
            $res = round($res, $precision);
            if ($res == 0) $res = 0.01;
            return "<span class='arr-up'><svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
<path d='M12.0341 21.375L12.0341 2.375M12.0341 2.375L6.30586 7.67681M12.0341 2.375L17.2656 7.32361' stroke='#0EAB44' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>$res%</span>";
        }
        if ($res < 0) {
            $res = round($res, $precision);
            if ($res == 0) $res = -0.01;

            return "<span class='arr-down'><svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
<path d='M11.5294 2.60156L11.5294 21.6016M11.5294 21.6016L6.00391 16.1831M11.5294 21.6016L16.6577 16.7872' stroke='#F42929' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>" . $res * (-1) . "%</span>";
        }

    }

    public function tsphp_performance_chart($table_type, $if_exist)
    {

        if ($if_exist) {
            global $wpdb;
            $tsphp_prices = $wpdb->get_results("SELECT * FROM " . $table_type . " ORDER BY trade_date ASC");
            //graph data, convert date to unix js format
            $tsphp_graph_data =
                array_map(function ($i) {
                    if ($i->trade_date && $i->total_val) {
                        return $darr = array(strtotime($i->trade_date) * 1000, floatval($i->total_val));
                    }
                }, $tsphp_prices);

            //percents
            $tsphp_calc_data =
                array_map(function ($i) {
                    if ($i->trade_date && $i->total_val) {
                        return $darr = array(strtotime($i->trade_date), floatval($i->total_val));
                    }
                }, $tsphp_prices);

            $curr_year_start = date('Y') . '-01-01';
            //        $curr_year_end = date('Y') . '-12-31';
            $curr_date = date('Y-m-d');
            $start_date = '2003-01-01';

            $ytd_start = $this->tsphp_closest($tsphp_calc_data, strtotime($curr_year_start), true);
            $ytd_end = $this->tsphp_closest($tsphp_calc_data, strtotime($curr_date), false);
            $prev_year_end = $this->tsphp_closest($tsphp_calc_data, strtotime("$curr_date -1 year"), true);
            $prev_3year_end = $this->tsphp_closest($tsphp_calc_data, strtotime("$curr_date -3 year"), true);
            $prev_5year_end = $this->tsphp_closest($tsphp_calc_data, strtotime("$curr_date -5 year"), true);
            $prev_10year_end = $this->tsphp_closest($tsphp_calc_data, strtotime("$curr_date -10 year"), true);
            $prev_max_year_end = $this->tsphp_closest($tsphp_calc_data, strtotime("$start_date"), true);

            $alert_name = '';
            if (strcmp($table_type, 'tsp_aggressive_table') == 0) {
                $alert_name = 'Aggressive';

            } elseif (strcmp($table_type, 'tsp_conservative_table') == 0) {
                $alert_name = 'Conservative';
            }
            include 'shortcode-tsphp_performance_chart.php';


        } else {
            return ' - No data - ';
        }

    }

    public function tsphp_performance_aggr_chart()
    {
        ob_start();

        $this->tsphp_performance_chart($this->tsphp_math_class->tsphp_aggressive_table, $this->tsphp_math_class->tsphp_aggressive_table_exist);
        return ob_get_clean();

    }

    public function tsphp_performance_cons_chart()
    {
        ob_start();

        $this->tsphp_performance_chart($this->tsphp_math_class->tsphp_conservative_table, $this->tsphp_math_class->tsphp_conservative_table_exist);
        return ob_get_clean();

    }

    /*
     * Performance tiles
     */
    public function tsphp_closest_tiles_date($dates, $findate, $next, $out_fund)
    {
        //search closest date, if end\start date not exist
        $newDates = array();

        foreach ($dates as $date) {
            $newDates[] = $date->date;
        }
        if ($next)
            sort($newDates);
        else
            arsort($newDates);

        $res = array();
        foreach ($newDates as $a) {
            if ($a >= $findate && $next) {
                $res = $a;
                break;
            } elseif ($a < $findate && !$next) {
                // if needed prev date, but no current
                $res = $a;
                break;
            }
        }
        foreach ($dates as $date) {
            if ($date->date == $res) {

//                var_dump(date('Y-m-d', $date->date));
//                echo '<br>';
//                print_r($date);
//                echo '<hr>';

                return $date->$out_fund;
            }
        }
    }

    public function tsphp_tile_constructor($data, $fund, $last, $week, $month, $ytd, $year1, $year2)
    {
        $graph_count = 5;
        $graph_data = array();
        foreach ($data as $dr) {
            if ($graph_count !== 0) {
                $graph_count--;
                $graph_data[] = array(($dr->date) * 1000, floatval($dr->$fund));
            } else {
                break;
            }
        }

        $last = $this->tsphp_closest_tiles_date($data, $last, true, $fund);

        $last_month_s = $this->tsphp_closest_tiles_date($data, strtotime(date('Y-m-01', $month)), false, $fund);
        $last_month_e = $this->tsphp_closest_tiles_date($data, strtotime(date('Y-m-t', $month)), true, $fund);

        $min_1y_s = $this->tsphp_closest_tiles_date($data, strtotime(date('Y', $year1) . '-01-01'), false, $fund);
        $min_1y_e = $this->tsphp_closest_tiles_date($data, strtotime(date('Y', $year1) . '-12-31'), false, $fund);

        $min_2y_s = $this->tsphp_closest_tiles_date($data, strtotime(date('Y', $year2) . '-01-01'), false, $fund);
        $min_2y_e = $this->tsphp_closest_tiles_date($data, strtotime(date('Y', $year2) . '-12-31'), true, $fund);

//        echo $fund . ' start<br>';
//        var_dump($min_2y_s);
//      var_dump(strtotime(date('Y', $year2) . '-01-01'));
//        echo '<br>';
//        echo '$min_2y_e end' . '<br>';
//        var_dump($min_1y_e);
//        var_dump(date('Y-01-01',$year2) . '-$year2 S');
//        var_dump(date('Y-12-31',$year2) . '-$year2 E');
////       var_dump(date('Y-m-d', $year2));
//        echo '<br>';

        $new_array = array(
            'week' => $this->tsphp_closest_tiles_date($data, $week, true, $fund),
            'month' => round((($last_month_e - $last_month_s) / $last_month_s) * 100, 2),
            'ytd' => $this->tsphp_closest_tiles_date($data, $ytd, false, $fund),
            'year' => round((($min_1y_e - $min_1y_s) / $min_1y_s) * 100, 2),
            'year2' => round((($min_2y_e - $min_2y_s) / $min_2y_s) * 100, 2),
            'graph_data' => array_reverse($graph_data),
        );
        array_walk($new_array, function (&$a, $k) use ($last) {
            if ($k == 'week' || $k == 'ytd') $a = round((($last - $a) / $a) * 100, 2);

        });
        return $new_array;
    }

    function tsphp_tiles_per($v, $prec)
    {
        if ($v >= 0) echo "<span class='arr-up'><svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
<path d='M12.0341 21.375L12.0341 2.375M12.0341 2.375L6.30586 7.67681M12.0341 2.375L17.2656 7.32361' stroke='#0EAB44' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>" . round($v, $prec) . "%</span>";
        if ($v < 0) echo "<span class='arr-down'><svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
<path d='M11.5294 2.60156L11.5294 21.6016M11.5294 21.6016L6.00391 16.1831M11.5294 21.6016L16.6577 16.7872' stroke='#F42929' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>" . round($v * (-1), $prec) . "%</span>";
    }

    public function tsphp_performance_tiles($table_prices, $if_exist)
    {
        if ($if_exist) {
            global $wpdb;
            $tsphp_prices = $wpdb->get_results("SELECT * FROM " . $table_prices . " ORDER BY date DESC");
            array_walk($tsphp_prices, function (&$a, $b) {
                $a->date = strtotime($a->date);
            });

            $curr_year_start = date('Y') . '-01-01';

            $last_import = date('Y-m-d', $tsphp_prices[0]->date);

            $last_import_uni = strtotime("$last_import");
            $last_1week = strtotime("$last_import - 1week");
            $last_1month = strtotime("$last_import - 1month");
            $last_ytd = strtotime("$curr_year_start");
            $last_1year = strtotime("$last_import - 1year");
            $last_2year = strtotime("$last_import - 2year");

            $fund_tiles = array(
                'g_fund' => $this->tsphp_tile_constructor(
                    $tsphp_prices,
                    'g_fund',
                    $last_import_uni,
                    $last_1week,
                    $last_1month,
                    $last_ytd,
                    $last_1year,
                    $last_2year
                ),
                'f_fund' => $this->tsphp_tile_constructor(
                    $tsphp_prices,
                    'f_fund',
                    $last_import_uni,
                    $last_1week,
                    $last_1month,
                    $last_ytd,
                    $last_1year,
                    $last_2year
                ),
                'c_fund' => $this->tsphp_tile_constructor(
                    $tsphp_prices,
                    'c_fund',
                    $last_import_uni,
                    $last_1week,
                    $last_1month,
                    $last_ytd,
                    $last_1year,
                    $last_2year
                ),
                's_fund' => $this->tsphp_tile_constructor(
                    $tsphp_prices,
                    's_fund',
                    $last_import_uni,
                    $last_1week,
                    $last_1month,
                    $last_ytd,
                    $last_1year,
                    $last_2year
                ),
                'i_fund' => $this->tsphp_tile_constructor(
                    $tsphp_prices,
                    'i_fund',
                    $last_import_uni,
                    $last_1week,
                    $last_1month,
                    $last_ytd,
                    $last_1year,
                    $last_2year
                )
            );

            include 'shortcode-tsphp_performance_tiles.php';


        } else {
            return ' - No data - ';
        }

    }

    public function tsphp_performance_tiles_run()
    {
        ob_start();
        $this->tsphp_performance_tiles($this->tsphp_import_class->tsphp_price_table, true);
        return ob_get_clean();
    }


//    public function tsphp_content_heading($alert_type, $if_exist)
    public function tsphp_content_heading($alert_type, $if_exist, $attr)
    {
        global $wpdb;

        if ($if_exist) {
//            $existing_columns = $wpdb->get_col("DESC {$alert_type}", 0);
//            $sql_cols = implode(', ', $existing_columns);
            $alocation_table_values = $wpdb->get_results("SELECT alert_date FROM " . $alert_type . " ORDER BY alert_date DESC LIMIT 1");
        } else {
            return ' - No data - ';
        }
        ?>

        <div class="content-heading">
            <h1><?php echo (!empty($attr['title'])) ? $attr['title'] : the_title(); ?></h1>
            <div class="content-print-options">
                <!--                <ul>-->
                <!--                    <li><a href="javascript:;" class="print2pdf print_hide" print-id="0" print-class="elementor-inner"-->
                <!--                           title="Print">Print Me</a></li>-->
                <!--                    <li><a href="javascript:;" class="getPDF content-render print_hide" pdf-id="0"-->
                <!--                           pdf-class="elementor-inner" title="Download as PDF">Download</a></li>-->
                <!--                </ul>-->
            </div>
            <div class="date">
                <span><?php echo date('F j, Y', strtotime($alocation_table_values[0]->alert_date)); ?></span></div>
        </div>

        <?php
    }

    public function tsphp_aggr_content_heading($sh_attr)
    {
        ob_start();
        $this->tsphp_content_heading($this->tsphp_math_class->tsphp_aggressive_alerts_table, $this->tsphp_math_class->tsphp_aggressive_alerts_table_exist, $sh_attr);
        return ob_get_clean();
    }

    public function tsphp_consv_content_heading($sh_attr)
    {
        ob_start();
        $this->tsphp_content_heading($this->tsphp_math_class->tsphp_conservative_alerts_table, $this->tsphp_math_class->tsphp_conservative_alerts_table_exist, $sh_attr);
        return ob_get_clean();
    }

}