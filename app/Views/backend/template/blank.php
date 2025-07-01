<html lang="en">

<?= $this->include('backend/template/css'); ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?= $this->include('backend/template/sidebar'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?= $this->include('backend/template/header'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    "/dashboard"
                    print(dashboard)
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?= $this->include('backend/template/footer'); ?>

            <!-- Script disini  -->
        </div>
    </div>
</body>

</html>