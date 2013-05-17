<?php

/*
 * ©2013 The Mustached Pi Project
 */

?>

<div class="row-fluid">
    <div class="span8">
        <h2><i class="icon-home"></i> Configure new house</h2>

        <form class="form-horizontal" method="POST" action="?p=house.new.ok">
            <div class="control-group">
                <label class="control-label" for="inputName">House name</label>
                <div class="controls">
                  <input type="text" name="inputName" id="inputName" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputAddress">Full address</label>
                <div class="controls">
                  <input type="text" name="inputAddress" id="inputAddress" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputCode">Secret code</label>
                <div class="controls">
                  <input type="text" pattern="[0-9]{6}" name="inputCode" id="inputCode" required>
                </div>
            </div>    
            <div class="control-group">
                <div class="controls">
                    <button type="subimt" class="btn btn-primary btn-large">
                        <i class="icon-thumbs-up"></i> Add the house
                    </button>
                </div>
            </div>

        </form>
    </div>

    <div class="span4">
        <div class="alert alert-info alert-block">
            <h4><i class="icon-info-sign"></i> Info</h4>
            <p>The <strong>secret code</strong> should be vocally
                synthesized by the MPi every 30 seconds, when turned on.</p>
            <p>Once connected, the MPi will welcome you.</p>
        </div>
    </div>

</div>