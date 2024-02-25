@if(!isset($isSwal) || $isSwal == false)
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    @endif
    @push('custom-script')
    <script>
        window.setTimeout(function() {
            $(".alert").not('.undismissable').fadeTo(1000, 0).slideUp(300, function(){
                $(this).slideUp(300); 
            });
        }, 5000);
    </script>
    @endpush
@elseif($isSwal == true)
    @push('custom-script')
        <script>
            function swalToast(type, message) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    icon: type,
                    title: message,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                })
            }
        </script>
        @if (session('success'))
        <script>
            swalToast("success", "{{ session('success') }}")
        </script>
        @endif
        @if (session('error'))
        <script>
            swalToast("error", "{{ session('error') }}")
        </script>
        @endif
    @endpush
@endif