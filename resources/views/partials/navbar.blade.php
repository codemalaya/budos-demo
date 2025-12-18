<div class="sticky-top">
    <nav class="navbar navbar-expand-lg bg-white" id="navbar">
        <div class="container">
            <a class="navbar-brand d-md-none d-block" href="/">
                <img src="{{ asset('assets/images/ddd.png') }}" alt="Bootstrap" width="125">
            </a>
            <button class="navbar-toggler border-0 rounded" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarsExample11" aria-controls="navbarsExample11" aria-expanded="false"
                aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-lg-flex" id="navbarsExample11">
                <a class="navbar-brand col-lg-3 me-0 d-none d-md-block" href="/">
                    <img src="{{ asset('assets/images/ddd.png') }}" alt="Bootstrap" width="125">
                </a>
                <ul class="navbar-nav col-lg-6 justify-content-lg-center">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li>
                </ul>
                <div class="d-lg-flex col-lg-3 justify-content-lg-end">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</div>
@push('script')
    <script>
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('shadow-sm');
            } else {
                navbar.classList.remove('shadow-sm');
            }
        });
    </script>
@endpush
