<?php

/**
 * Twilio Bulk SMS Admin Dashboard - View All Contacts
 */

global $wpdb;
$contacts = $wpdb->prefix . 'twilio_bulk_contacts';
$contacts_query = $wpdb->get_results("SELECT * FROM $contacts ORDER BY contact_date_updated ASC", ARRAY_A);
foreach ($contacts_query as $contact) {
    echo '<tr>';
    echo '<td>Contact Name: ' . $contact['contact_name'] . '</td>';
}

?>

<div class="bootstrap-wrapper">
    <div class="jumbotron ">
        <h1>Twilio Bulk Messaging</h1>
        <p>View and manage your contacts</p>
        <!-- Button trigger modal -->

        <button type="button" class="btn btn-outline-primary btn-lg" data-toggle="modal" data-target="#uploadModal">
            Upload New Contact List
        </button>
    </div>


    <!-- Modal -->
    <div class="modal fade mt-5" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="modalTitleID" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleID">Upload New Contact List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Upload Contact List Form -->
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="">
                                    <div class="form-group">
                                        <label for="file">Upload Contact List</label>
                                        <input type="file" class="form-control-file" id="twilio-file-upload" aria-describedby="fileHelp" accept=".xlsx, .xls, .csv">
                                        <small id="fileHelp" class="form-text text-muted">Please upload a valid .xlsx file.</small>
                                    </div>
                                    <div class="container d-none" id="twilio-contact-upload-information">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>File Stats</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>Bulk Output:</p>
                                                        <p id="twilio-contact-upload-output"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <p>Total Contacts: <span id="twilio-contact-upload-rows">0</span></p>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="contact-submit" class="btn btn-outline-primary">Upload</button>
                                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                <script type="text/javascript">
                                    // Reset form when Cancel button is clicked
                                    jQuery('#uploadModal').on('hidden.bs.modal', function() {
                                        jQuery(this).find('form')[0].reset();
                                    });
                                </script>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->




    <div class="shadow p-4 mb-4 bg-white col-sm-5">
        <h2>Your Contacts</h2>
        <p><span class="badge badge-pill badge-secondary">TODO:</span> list the most recently contacted individuals <i class="fa fa-spinner" aria-hidden="true"></i></p>
    </div>




</div>