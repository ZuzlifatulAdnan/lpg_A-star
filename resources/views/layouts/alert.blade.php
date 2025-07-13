{{-- @if ($message = Session::get('success'))
    <div class="alert alert-light-success color-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif($message = Session::get('danger'))
<div class="alert alert-light-danger color-danger alert-dismissible fade show">
    <i class="bi bi-check-circle"></i>
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@else    
@endif --}}
<!-- resources/views/components/alert.blade.php -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('danger'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session("danger") }}',
                icon: 'success',
                confirmButtonText: 'Coba Lagi'
            });
        @endif
        
        @if (session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session("error") }}',
                icon: 'error',
                confirmButtonText: 'Coba Lagi'
            });
        @endif

        @if (session('warning'))
            Swal.fire({
                title: 'Peringatan!',
                text: '{{ session("warning") }}',
                icon: 'warning',
                confirmButtonText: 'Hati-Hati'
            });
        @endif
    });
</script>

