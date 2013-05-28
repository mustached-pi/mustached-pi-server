<?php

/*
 * ©2013 The Mustached Pi Project
 */

$p = (int) $_GET['id'];
$p = new \MPi\Entity\Port($p);


$times = [1, 5, 15, 30, 60, 120];
$time = @$_GET['inputTime'];
if (!$time) {
    $time = $times[1]; // Default value
}

$min = new \DateTime;
$min->modify("-$time minutes");

$r = [];
foreach ( $p->values($min) as $v ) {
    $r[] = [(int) $v->timestamp, (int) $v->percent()];
}
$num = count($r);

?>

<!-- Refresh every ten seconds -->
<meta http-equiv="refresh" content="10" />
 
<div class="row-fluid">
    <div class="span4 align-center">
        <h2>Port graph</h2>
        <a href="?p=dash&id=<?php echo $p->house; ?>" class="btn btn-inverse">
            <i class="icon-reply"></i> Back to the dashboard
        </a>
    </div>
    
    <div class="span4 align-center">
        Port Name: <code><?php echo $p->name; ?></code><br />
        Port ID: <code><?php echo $p->id; ?></code><br />
        <hr />
        Collected samples:
        <strong><?php echo $num; ?></strong>
    </div>
     
    <div class="span4 align-center">
        <form action="?" method="GET">
            <input type="hidden" name="p" value="graph" />
            <input type="hidden" name="id" value="<?php echo $p->id; ?>" />
            Show values of the last 
            <select name="inputTime">
                <?php foreach ( $times as $t ) { ?>
                <option value="<?php echo $t; ?>"
                        <?php if ( $t == $time ) {?>selected="selected"<?php } ?>>
                    <?php echo $t; ?> minutes
                </option>
                <?php } ?>
            </select>
            <br />
            <button type="submit" class="btn btn-block">
                <i class="icon-refresh"></i> Refresh
            </button>
        </form>
    </div>
</div>
 



<div class="row-fluid">
    
    <div id="graph" class="span12" style="min-height: 400px;"></div>
    
</div>

<script type="text/javascript">
$(document).ready( function() {
    $.plot(
        "#graph",
        [<?php echo json_encode($r); ?>],
        {
            xaxis: {
                mode: "time"
            }
        }
    );
});    
</script>
