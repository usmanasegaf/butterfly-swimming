<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Butterfly Swimming - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="bg-light">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg rounded-5">
                    <div class="card-body p-5 text-center">
                        <h2 class="fw-bold mb-4 text-primary">Selamat Datang</h2>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Pastikan bagian ini ada untuk menampilkan pesan sukses --}}
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('login.action') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <div class="form-floating">
                                    <input type="email" name="email" class="form-control rounded-pill" id="email" placeholder="Masukkan Alamat Email" value="{{ old('email') }}" required autofocus>
                                    <label for="email" class="form-label">Email</label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="form-floating">
                                    <input type="password" name="password" class="form-control rounded-pill" id="password" placeholder="Password" required>
                                    <label for="password" class="form-label">Password</label>
                                </div>
                            </div>
                            <div class="form-check d-flex justify-content-start mb-4">
                                {{-- Pastikan id 'showPasswordToggle' dan 'password' sesuai --}}
                                <input class="form-check-input me-2 rounded border-primary" type="checkbox" id="showPasswordToggle">
                                <label class="form-check-label" for="showPasswordToggle">
                                    Tampilkan Kata Sandi
                                </label>
                            </div>
                            <button class="btn btn-primary btn-lg w-100 rounded-pill" type="submit">Login</button>
                        </form>
                        <hr class="my-4">
                        <p class="mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold">Daftar di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const floatingInputs = document.querySelectorAll('.form-floating input');
        floatingInputs.forEach(input => {
            if (input.value) {
                const label = input.nextElementSibling;
                if (label && label.classList.contains('form-label')) {
                    label.classList.add('floating');
                }
            }

            input.addEventListener('focus', function() {
                const label = this.nextElementSibling;
                if (label && label.classList.contains('form-label')) {
                    label.classList.add('floating');
                }
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    const label = this.nextElementSibling;
                    if (label && label.classList.contains('form-label')) {
                        label.classList.remove('floating');
                    }
                }
            });
        });

        // SKRIP BARU UNTUK TOGGLE SHOW PASSWORD
        const passwordField = document.getElementById('password');
        const showPasswordToggle = document.getElementById('showPasswordToggle');

        if (showPasswordToggle && passwordField) {
            showPasswordToggle.addEventListener('change', function() {
                if (this.checked) {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            });
        }
    });
    </script>
</body>
</html>
