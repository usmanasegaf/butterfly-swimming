<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Butterfly Swimming - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body class="bg-light">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                <div class="card shadow-lg rounded-5">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-4 text-primary text-center">Buat Akun Baru</h2>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('register.save') }}" method="POST">
                            @csrf
                            <div class="mb-4 ps-2">
                                <label for="role" class="block mb-1">Daftar Sebagai</label>
                                <select name="role" id="role" class="w-full border px-3 py-2 rounded" required>
                                    <option value="guru">Guru</option>
                                    <option value="murid">Murid</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <div class="form-floating">
                                    <input type="text" name="name" class="form-control rounded-pill"
                                        id="name" placeholder="Nama Lengkap" value="{{ old('name') }}">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="form-floating">
                                    <input type="email" name="email" class="form-control rounded-pill"
                                        id="email" placeholder="Alamat Email" value="{{ old('email') }}">
                                    <label for="email" class="form-label">Email</label>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input type="password" name="password" class="form-control rounded-pill"
                                            id="password" placeholder="Password">
                                        <label for="password" class="form-label">Password</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" name="password_confirmation"
                                            class="form-control rounded-pill" id="password_confirmation"
                                            placeholder="Ulangi Password">
                                        <label for="password_confirmation" class="form-label">Ulangi Password</label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-lg w-100 rounded-pill" type="submit">Daftar
                                Akun</button>
                        </form>
                        <hr class="my-4">
                        <p class="mb-0 text-center">Sudah punya akun? <a href="{{ route('login') }}"
                                class="text-primary fw-bold">Login di sini</a></p>
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
        });
    </script>
</body>

</html>
