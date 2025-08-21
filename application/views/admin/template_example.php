<?php
/**
 * Admin Page Template Example
 * 
 * This file shows how to use the common admin components:
 * - Header (includes navigation, meta tags, and common styles)
 * - Sidebar (includes navigation menu with active states)
 * - Footer (includes common scripts and closing tags)
 * 
 * To use this template for a new admin page:
 * 1. Replace the content between the header and footer includes
 * 2. Update the page title and breadcrumbs
 * 3. Add your specific functionality
 */

// Set the page title
$title = 'Your Page Title';
?>

<?php $this->load->view('admin/includes/header'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            <?php $this->load->view('admin/includes/sidebar'); ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">Your Page Title</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Your Page</li>
                                </ol>
                            </nav>
                        </div>
                        <div>
                            <!-- Add action buttons here -->
                            <a href="<?php echo base_url('admin/dashboard'); ?>" class="btn btn-outline-secondary btn-action">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Your Page Content Goes Here -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Page Content
                                </h5>
                            </div>
                            <div class="card-body">
                                <p>This is where your page content goes.</p>
                                
                                <!-- Example of using common search/filter functions -->
                                <div class="search-box">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select" id="filterSelect">
                                                <option value="all">All Items</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                                <i class="fas fa-times me-2"></i>Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Example content container -->
                                <div id="contentContainer">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Example Item 1</h6>
                                                    <p class="card-text">This is an example item.</p>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-primary btn-sm btn-action">Edit</button>
                                                        <button class="btn btn-danger btn-sm btn-action">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page-specific styles -->
<style>
/* Add your page-specific styles here */
</style>

<!-- Page-specific scripts -->
<script>
// Example of using common functions from footer
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSelect');
    
    searchInput.addEventListener('input', function() {
        performSearch(this.value, '#contentContainer', '.col-md-4', ['title', 'content']);
    });
    
    filterSelect.addEventListener('change', function() {
        performFilter(this.value, '#contentContainer', '.col-md-4', 'status');
    });
});

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterSelect').value = 'all';
    // Show all items
    document.querySelectorAll('#contentContainer .col-md-4').forEach(item => {
        item.style.display = 'block';
    });
}
</script>

<?php $this->load->view('admin/includes/footer'); ?> 