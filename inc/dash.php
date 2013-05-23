<?php

/*
 * ©2013 The Mustached Pi Project
 */

$id     = $_GET['id'];
$house  = new \MPi\Entity\House($id);

?>

<div class="row-fluid">
    
    <div class="span9">
        <h2><i class="icon-home"></i> <?php echo $house->name; ?></h2>
        <p><i class="icon-globe"></i> <?php echo $house->address; ?></p>
    </div>
    
    <div class="span3">
        <i class="icon-code"></i> House ref. code<br />
        <code>#<?php echo $house->code; ?></code>
    </div>
</div>

<div class="row-fluid">
    <table class="table table-bordered table-striped">

        <thead>
            <th>Port</th>
            <th>Name</th>
            <th>Type</th>
            <th>Last value</th>
            <th>Last updated</th>
            <th>Configured</th>
        </thead>

        <?php foreach ( $house->ports() as $port ) { 
            $i = $port->instance();
            ?>
        <tr>
            <td>#<strong><?php echo $port->num; ?></strong></td>

            <td><?php echo $port->name; ?></td>

            <td>
                <?php if ($port->type == INPUT) { ?>
                    Input
                <?php } else { ?>
                    Output
                <?php } ?>
            </td>


            <td>
                <?php if ($port->type == INPUT) { ?>
                    <strong>
                        <?php echo $i->percent(); ?>%
                    </strong>
                <?php } else { ?>
                    <?php if ( $i->value ) { ?>
                        <i class="icon-circle text-success"></i> On
                    <?php } else { ?>
                        <i class="icon-circle-blank text-error"></i> Off
                    <?php } ?>
                <?php } ?>
                        
                        <br />

                <?php if ( $i instanceof MPi\Entity\Output ) { ?>
                    <a href="?p=port.change&house=<?php echo $house->id; ?>&num=<?php echo $port->num; ?>">
                       <i class="icon-bolt"></i> Change
                    </a>
                <?php } else { ?>
                    <a href="?p=graph&id=<?php echo $port->id; ?>" title="Statistics">
                        <i class="icon-bar-chart"></i> Graph
                    </a>
                <?php } ?>

            </td>
            <td><small><?php echo $i->timestamp()->format('d-m-Y H:i:s'); ?></small></td> 
            <td><small><?php echo $port->timestamp()->format('d-m-Y H:i:s'); ?></small></td>

        </tr>
        <?php } ?>

        <tr>
            <td colspan="7">
                <a href="?p=port&house=<?php echo $house->id; ?>">
                    <i class="icon-plus"></i> add port configuration
                </a>
            </td>
        </tr>
    </table>
</div>