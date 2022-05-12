<?php

/** Twilio Bulk SMS Upload Form
 * 
 */

?>

<div class="bootstrap-wrapper">
    <div class="jumbotron">
        <h1>Upload Contact List</h1>
        <p>Upload a file containing your contacts to include in a messaging campaign!</p>
    </div>

    <div class="shadow p-4 mb-4 bg-white col-sm-5">
        <h2>Upload a File</h2>
        <p>
        <form action="" method="POST" enctype="multipart/form-data" autocomplete="new-password">
            <!-- File Upload: .xlsx, .xls, and .csv -->
            <div class="form-group row">
                <label for="file" class="col-sm-2 col-form-label">File</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="file" name="file" required accept=".xls,.xlsx,.csv">
                </div>
            </div>

            <!-- Submit -->
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" id="contacts-upload" class="btn btn-primary" name="contacts-upload">Upload</button>
                </div>
            </div>
        </p>
    </div>
    
    <div class="shadow p-4 mb-4 bg-white col-sm-5 d-none" id="upload-response">
        <h2>Select Required Fields:</h2>
        <div id="upload-response-fields">
            <p></p>
        </div>
    </div>
</form>
    
    <!-- POST Dump -->
    <div class="shadow p-4 mb-4 bg-white col-sm-5" id="post_dump">
        <h2>Post Dump</h2>
        <?php
        var_dump($_POST);
        ?>
    </div>

    <!-- FILES Dump -->
    <div class="shadow p-4 mb-4 bg-white col-sm-5" id="files_dump">
        <h2>Files Dump</h2>
        <?php
        var_dump($_FILES);
        ?>
    </div>

</div>