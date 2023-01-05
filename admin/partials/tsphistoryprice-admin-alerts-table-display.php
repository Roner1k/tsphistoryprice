<?php
?>
<div class="the-content tsphs_table_content">
    <h1>Alert dates</h1>
    <?php
    $alerts_dates_aggressive  = new Tsphp_alert_dates_build();
    $alerts_dates_aggressive->tsphp_alert_dates_buid($alert_gen_type);
    ?>

</div>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->