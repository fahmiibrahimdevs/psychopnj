<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard</h4>
                </div>
                <div class="card-body">
                    <p>Selamat datang, {{ Auth::user()->name }}!</p>
                    <p>
                        Role Anda saat ini:
                        @foreach (Auth::user()->getRoleNames() as $role)
                            <span class="badge badge-primary">{{ $role }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
