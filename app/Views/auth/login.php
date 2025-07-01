<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="/Assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/Assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="/Assets/vendor/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card p-4 shadow" style="max-width: 450px ; width: 100%;">
            <div class="text-center mb-4">
                <div class="flex justify-center">
                    <i class="fas fa-store fa-4x" style="color: #4e73df;"></i>
                </div>
                <h2 class="mt-4 text-3xl font-extrabold text-primary" style="font-weight: 800 !important;">
                    RX-3 MANTAN
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    Silahkan login untuk mengakses sistem
                </p>
            </div>
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>
            <form action='/login' method='post'>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script src="/Assets/vendor/jquery/jquery.min.js"></script>
    <script src="/Assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/Assets/js/sb-admin-2.min.js"></script>
    <script src="/Assets/vendor/fontawesome-free/js/all.min.js"></script>
    <script src="/Assets/js/script.js"></script>

</body>

</html>