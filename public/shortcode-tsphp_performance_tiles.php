<?php


?>
<div class="tsp-performance-tiles">
    <div class="heading">
        <h4>Fund Performance</h4>
        <div class="date">As of <?php echo date_format(date_create($last_import), 'F dS, Y'); ?>
        </div>
        <br>
    </div>
    <div class="tsp-performance-tiles-wrap">

        <?php
        //        var_dump($fund_tiles);
        foreach ($fund_tiles as $tk => $tile) :
            $tile_name = ucfirst(str_replace('_', ' ', $tk)); ?>

            <div class='performance-tile pt-single pt-<?php echo $tk; ?>' data-tsp_pt="<?php echo $tk; ?>">
                <div class="pt-head pt-row">
                    <div class="pt-left">
                        <div class='pt-title'><span class='pt-color'></span><?php echo $tile_name; ?>
                        </div>
                        <div class='pt-label'>1 week</div>
                    </div>
                    <div class='pt-value'> <?php $this->tsphp_tiles_per($tile['week'], 2); ?></div>
                </div>
                <div id="pt-graph-data-<?php echo $tk; ?>" style="display:none;">
                    <?php echo json_encode($tile['graph_data']); ?>
                </div>
                <div id="pt-graph-container-<?php echo $tk; ?>" class="pt-graph-container">
                </div>


                <div class='pt-data'>
                    <div class="pt-row">
                        <div class='pt-label'>1 month</div>
                        <div class='pt-value'> <?php $this->tsphp_tiles_per($tile['month'], 2); ?></div>
                    </div>

                    <div class="pt-row">
                        <div class='pt-label'>YTD</div>
                        <div class='pt-value'> <?php $this->tsphp_tiles_per($tile['ytd'], 2); ?></div>
                    </div>

                    <div class="pt-row">
                        <div class='pt-label'><?php
                            //                            echo date('Y', strtotime('-1 year', time()));
                            echo date('Y', $last_1year);
                            ?></div>
                        <div class='pt-value'> <?php $this->tsphp_tiles_per($tile['year'], 2); ?></div>
                    </div>

                    <div class="pt-row">
                        <div class='pt-label'><?php
                            //                            echo date('Y', strtotime('-2 year', time()));
                            echo date('Y', $last_2year);;

                            ?></div>
                        <div class='pt-value'> <?php $this->tsphp_tiles_per($tile['year2'], 2); ?></div>
                    </div>


                </div>


            </div>

        <?php endforeach; ?>
    </div>
</div>

