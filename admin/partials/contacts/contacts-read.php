<?php

/**
 * Twilio Bulk SMS Admin Dashboard - View All Contacts
 */

use \TwilioBulkContacts;

$contacts = new TwilioBulkContacts;

print_r($contacts);
print_r($contacts->get_contacts());

?>

<div class="bootstrap-wrapper">
    <div class="jumbotron ">
        <h1>Bulk Messaging Contacts</h1>
        <p>View and manage your contacts</p>
    </div>





    <div class="shadow p-4 mb-4 bg-white col-sm-5">
        <h2>Your Contacts</h2>
        <p><span class="badge badge-pill badge-secondary">TODO:</span> list the most recently contacted individuals <i class="fa fa-spinner" aria-hidden="true"></i></p>
    </div>




</div>