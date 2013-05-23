<?php

/*
 * ©2013 The Mustached Pi Project
 */

$house = $_GET['house'];
$house = new \MPi\Entity\House($house);


?>

<div class="row-fluid">
    <div class="span8">
        <h2><i class="icon-plus"></i> Add port configuration</h2>

        <form class="form-horizontal" method="POST" action="?p=port.ok">
            <input type="hidden" name="house" value="<?php echo $house->id; ?>" />
            <div class="control-group">
                <label class="control-label" for="inputNum">Port no.</label>
                <div class="controls">
                    <select name="inputNum" required>
                        <?php for ( $i = 0; $i < 16; $i++ ) { ?>
                            <option value="<?php echo $i; ?>">Port #<?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputName">Port name</label>
                <div class="controls">
                    <input type="text" id="inputName" name="inputName" value="Port" />
                </div>
            </div>           
            <div class="control-group">
                <label class="control-label" for="inputType">Direction</label>
                <div class="controls">
                    <select name="inputType" required>
                        <option value="<?php echo INPUT; ?>">Input (sensor)</option>
                        <option value="<?php echo OUTPUT; ?>">Output (switch)</option>
                    </select>
                </div>
            </div>           
            <div class="control-group">
                <div class="controls">
                    <button type="subimt" class="btn btn-primary btn-large">
                        <i class="icon-plus"></i> Add configuration
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>