<?php ?>
<form method="POST" class="form tsp-update-form">

    <dl>
        <dd>
            <h3>Update <?php echo $alert_name . ' Alert ' . $date_row->trade_date; ?></h3>
            <fieldset>
                <label for="alert_date">Alert date</label>
                <input id="alert_date" name="alert_date" type="date"  value="<?php echo $date_row->alert_date; ?>" disabled>

                <label for="trade_date">Trade date</label>
                <input id="trade_date" name="trade_date" type="date" min="<?php echo $date_row->alert_date ?>"
                       value="<?php echo $date_row->trade_date; ?>">

            </fieldset>

            <fieldset class="tsp-perc">
                <fieldset>
                    <label for="g_f_per">G Fund%</label>
                    <input id="g_f_per" name="g_f_per" type="number"
                           value="<?php echo $date_row->g_f_per; ?>" <?php //echo $editable ? '' : 'disabled'; ?>>
                </fieldset>
                <fieldset>
                    <label for="f_f_per">F Fund%</label>
                    <input id="f_f_per" name="f_f_per" type="number"
                           value="<?php echo $date_row->f_f_per; ?>" <?php //echo $editable ? '' : 'disabled'; ?>>
                </fieldset>
                <fieldset>
                    <label for="c_f_per">C Fund%</label>
                    <input id="c_f_per" name="c_f_per" type="number"
                           value="<?php echo $date_row->c_f_per; ?>" <?php //echo $editable ? '' : 'disabled'; ?>>
                </fieldset>
                <fieldset>
                    <label for="s_f_per">S Fund%</label>
                    <input id="s_f_per" name="s_f_per" type="number"
                           value="<?php echo $date_row->s_f_per; ?>" <?php //echo $editable ? '' : 'disabled'; ?>>
                </fieldset>
                <fieldset>
                    <label for="i_f_per">I Fund%</label>
                    <input id="i_f_per" name="i_f_per" type="number"
                           value="<?php echo $date_row->i_f_per; ?>" <?php //echo $editable ? '' : 'disabled'; ?>>
                </fieldset>
            </fieldset>
            <fieldset>
                <label for="alert_reason">Alert reason</label>
                <textarea name="alert_reason"
                          id="alert_reason"  <?php // echo ($editable) ? '' : 'disabled'; ?> ><?php echo $date_row->alert_reason; ?></textarea>

                <label for="alert_post_link">Post link</label>
                <input id="alert_post_link" name="alert_post_link" type="text"
                       value="<?php echo $date_row->alert_post_link; ?>" <?php //echo $editable ? '' : 'disabled'; ?>>
            </fieldset>


        </dd>
        <dt>&nbsp;</dt>
        <dd>

            <input type="submit" name="update-current-alert-date" value="Update"
                   class="button button-primary" <?php //echo $editable ? '' : 'disabled'; ?>/>
            <a href="admin.php?page=tsp-alert-dates-<?php echo strtolower($alert_name); ?>" class="button">Back</a>
        </dd>
    </dl>

</form>


