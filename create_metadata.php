<?php
include 'header.php';
$response = $client->getPages();
$result = json_decode($response, true);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Create Metadata</h1>
        <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create Metadata</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create Metadata</h3>
            </div>
            <div class="box-body">
                <div class="alert alert-danger" id="sendRefErrors" style="display: none;"></div>
                <div class="alert alert-success" id="sendRefSuccess" style="display: none;"></div>
                <form id="sendReferenceForm">
                    <div class="form-group">
                        <label for="title">Page <span class="text-danger">*</span></label>
                        <select type="text" class="form-control" id="page_id" name="page_id">
                            <option value="">Please select page</option>
                            <?php foreach ($result as $row): ?>
                                <option value="<?= $row['id']; ?>">
                                    <?= $row['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description"
                            placeholder="Enter description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="keywords">Keywords <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="keywords" name="keywords"
                            placeholder="Enter keywords"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="submitRefBtn">
                            <i class="fa fa-paper-plane"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-default">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>
<script>
    $(function () {
        // Send Reference Form
        $('#sendReferenceForm').on('submit', async function (e) {
            e.preventDefault();

            const page_id = $('#page_id').val().trim();
            const title = $('#title').val().trim();
            const description = $('#description').val().trim();
            const keywords = $('#keywords').val().trim();

            // Clear previous messages
            $('#sendRefErrors').html("").hide();
            $('#sendRefSuccess').html("").hide();

            const submitBtn = $('#submitRefBtn');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Sending...').prop('disabled', true);

            try {
                const response = await fetch('bridge.php?mode=create_metadata', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        page_id,
                        title,
                        description,
                        keywords
                    })
                });

                const res = await response.json();
                console.log(res);

                if (response.ok) {
                    $('#sendRefSuccess').html('Metadata created successfully').show();
                    $('#sendReferenceForm')[0].reset();
                    // Auto-hide success message after 5 seconds
                    setTimeout(() => {
                        $('#sendRefSuccess').fadeOut();
                    }, 5000);
                } else {
                    if (res.error) {
                        $('#sendRefErrors').html(res.error).show();
                    } else if (res.errors) {
                        let error = `<ul>` + res.errors.map((err) => `<li>${err}</li>`).join('') + `</ul>`;
                        $('#sendRefErrors').html(error).show();
                    } else {
                        $('#sendRefErrors').html("Unknown error occurred.").show();
                    }
                }
            } catch (err) {
                console.error('Error:', err);
                $('#sendRefErrors').html('Network error. Please try again.').show();
            } finally {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
</script>