<?php
/*
 * (c)2013 The Mustached Pi Project
 */

privatePage();

?>

<div class="row-fluid">
    
    <div class="span4 hidden-phone">
        <h3><i class="icon-user"></i> You</h3>
        <p><strong><?php echo $me->name; ?></strong><br /><?php echo $me->email; ?></p>
        <p><a href="#"><i class="icon-pencil"></i> Edit details or password</a>.</p>
        <hr />
        <p><a class="btn btn-inverse" href="?p=logout">Log me out</a>.</p>
   
    </div>
    
    <div class="span8">
        
        <?php
        $houses = $me->houses();
        ?>
        
        <h2><i class="icon-home"></i> Houses
            <sup class="badge badge-inverse"><?php echo count($houses); ?></sup>
        </h2>
        
        <p>Here's a list of houses associated to your user account:</p>
        

        <ul>
            <?php foreach ( $houses as $house ) { ?>
                <li><?php echo $house->name; ?></li>
            <?php } ?>
        </ul>
        
        <?php if (!$houses) { ?>
        <p class="text-error"><i class="icon-warning-sign"></i> No houses yet!</p>
        <?php } ?>
        
        <p><a href="#" class="btn"><i class="icon-plus"></i> Click here to add a new house</a></p>
        
        
    </div>
</div>
