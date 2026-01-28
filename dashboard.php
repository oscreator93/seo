<?php
include 'header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Dashboard</h1>
        <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">SEO Metadata</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-sm btn-primary" id="refreshReferences">
                        <i class="fa fa-refresh"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="alert alert-info" id="refLoading" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i> Loading SEO list...
                </div>
                <div class="alert alert-warning" id="refNoData" style="display: none;">
                    <i class="fa fa-info-circle"></i> No SEO list found.
                </div>
                <div class="table-responsive" id="referencesTableContainer" style="display: none;">
                    <table class="table table-bordered table-striped table-hover" id="referencesTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Page</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Keywords</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="referencesTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>
<script>
    $(function () {
        // Load references on page load
        loadReferences();

        // Refresh references button
        $('#refreshReferences').on('click', function () {
            loadReferences();
        });

        // Load References Function
        async function loadReferences() {
            $('#refLoading').show();
            $('#refNoData').hide();
            $('#referencesTableContainer').hide();

            try {
                const response = await fetch('bridge.php?mode=get_seo_list', {
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' }
                });

                const result = await response.json();
                $('#refLoading').hide();

                if (response.ok) {
                    const tbody = $('#referencesTableBody');
                    tbody.empty();

                    result.forEach((data, index) => {

                        const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${data.name || 'N/A'}</td>
                            <td>${data.title || 'N/A'}</td>
                            <td>${data.description || 'N/A'}</td>
                            <td>${data.keywords || 'N/A'}</td>
                            <td>
                                <a target='_blank' href='edit_metadata.php?id=${data.id}'>
                                    <button class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </a>
                                <a onclick='deleteMetadata(${data.id})'>
                                    <button class="btn btn-sm btn-danger me-1" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                    `;
                        tbody.append(row);
                    });

                    $('#referencesTableContainer').show();
                } else {
                    $('#refNoData').show();
                }
            } catch (err) {
                console.error('Error:', err);
                $('#refLoading').hide();
                $('#refNoData').html('<i class="fa fa-exclamation-triangle"></i> Error loading metadata. Please try again.').show();
            }
        }
    });
    async function deleteMetadata(id) {
        if (confirm("Are you sure you want to delete?")) {
            try {
                const response = await fetch(`bridge.php?mode=delete_metadata&id=${id}`, {
                    method: "GET",
                    headers: { "Content-Type": "application/json" },
                });

                if (response.ok) {
                    // ✅ Success
                    location.reload();
                } else {
                    // ❌ Handle server errors
                    const res = await response.json();
                    alert(res.error);
                }
            } catch (err) {
                alert("Network error or server unreachable.");
            }
        }
    }
</script>