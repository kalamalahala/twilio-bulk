<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/kalamalahala/
 * @since      1.0.0
 *
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->



<div class="bootstrap-wrapper">
    <form>
        <h1 id="twilio-header">Hello World</h1>
        <button class="btn btn-primary">Click me</button>
        <div class="form-group row">
            <label for="text" class="col-4 col-form-label">Text Field</label>
            <div class="col-8">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <input id="text" name="text" type="text" class="form-control" placeholder="Enter your Twilio SID here...">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-8">
                <button name="submit" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>