<?php

/*
 * ©2013 The Mustached Pi Project
 */

$p = (int) $_GET['id'];
$p = new \MPi\Entity\Port($p);

$r = [];
foreach ( $p->values() as $v ) {
    $r[] = [(int) $v->timestamp, (int) $v->value];
}

?>
 <meta http-equiv="refresh" content="10" />

<h2>Port graph</h2>

<a href="?p=dash&id=<?php echo $p->house; ?>">
    <i class="icon-reply"></i> Back to the dashboard
</a>

<div class="row-fluid">
    
    <div id="graph" class="span12" style="min-height: 400px;"></div>
    
</div>

<script type="text/javascript">
$(document).ready( function() {
    $.plot(
        "#graph",
        [<?php echo json_encode($r); ?>]
    );
});    
</script>
