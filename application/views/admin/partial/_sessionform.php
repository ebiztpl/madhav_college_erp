
<label for="exampleInputEmail1">Session</label><small class="req"> </small>
 
        <select class="form-control" id="ritik_custom" name="popup_session">
            <?php
foreach ($sessionList as $session_key => $session_value) {
    ?>
                <option value="<?php echo $session_value['id']; ?>" <?php
if ($sessionData['session_id'] == $session_value['id']) {
        echo "selected='selected'";
    }
    ?>><?php echo $session_value['session']; ?></option>
                        <?php
}
?>
        </select>
    


