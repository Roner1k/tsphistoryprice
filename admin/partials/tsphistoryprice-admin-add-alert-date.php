<?php

?>
<h3>New <?php echo $alert_name; ?> Alert </h3>
<form method="POST" class="form tsp-add-alert-form tsp-update-form">

    <dl>

        <dd>

            <fieldset>
                <label for="alert_date">Alert date</label>
                <input id="alert_date" name="alert_date" type="date" min="2003-01-01" required>

                <label for="trade_date">Trade date</label>
                <input id="trade_date" name="trade_date" type="date" min="2003-01-01" required >

            </fieldset>

            <fieldset class="tsp-perc">
                <fieldset>
                    <label for="g_f_per">G Fund%</label>
                    <input id="g_f_per" name="g_f_per" type="number" value="0" required >
                </fieldset>
                <fieldset>
                    <label for="f_f_per">F Fund%</label>
                    <input id="f_f_per" name="f_f_per" type="number" value="0" required >
                </fieldset>
                <fieldset>
                    <label for="c_f_per">C Fund%</label>
                    <input id="c_f_per" name="c_f_per" type="number" value="0" required >
                </fieldset>
                <fieldset>
                    <label for="s_f_per">S Fund%</label>
                    <input id="s_f_per" name="s_f_per" type="number" value="0" required >
                </fieldset>
                <fieldset>
                    <label for="i_f_per">I Fund%</label>
                    <input id="i_f_per" name="i_f_per" type="number" value="0" required >
                </fieldset>
            </fieldset>
            <fieldset>
                <label for="alert_post_link">Post link</label>
                <input id="alert_post_link" name="alert_post_link" type="text" >
            </fieldset>
            <fieldset>
                <label for="alert_reason">Alert reason</label>
                <textarea name="alert_reason" id="alert_reason" > </textarea>
            </fieldset>
        </dd>
        <dt>&nbsp;</dt>
        <dd>
            <input type="submit" name="tsp-new-alert-date" value="Save" class="button button-primary" />
            <a href="admin.php?page=tsp-alert-dates-<?php echo strtolower($alert_name); ?>" class="button">Back</a>
        </dd>
    </dl>


</form>


