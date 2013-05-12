<?php

/*
 * ©2013 The Mustached Pi Project
 */

?>

<div class="row-fluid">
    <div class="span8">
        <h2><i class="icon-plus"></i> Register now</h2>

        <form class="form-horizontal" method="POST" action="?p=register.ok">
            <div class="control-group">
                <label class="control-label" for="inputEmail">Email</label>
                <div class="controls">
                  <input type="email" name="inputEmail" id="inputEmail" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputPassword">Password</label>
                <div class="controls">
                  <input type="password" name="inputPassword" id="inputPassword" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputName">Full name</label>
                <div class="controls">
                  <input type="text" name="inputName" id="inputName" required>
                </div>
            </div>    
            <div class="control-group">
                <div class="controls">
                    <button type="subimt" class="btn btn-primary btn-large">
                        <i class="icon-thumbs-up"></i> Gimme my account
                    </button>
                </div>
            </div>

        </form>
    </div>

    <div class="span4">
        <div class="alert alert-info alert-block">
            <h4><i class="icon-info-sign"></i> Info</h4>
            <p>Please fill the form with your details.</p>
            <p>We will give you immediate access.</p>
        </div>
    </div>

</div>