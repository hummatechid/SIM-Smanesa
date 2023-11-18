@extends('admin.layouts.app')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">jQuery Datatable</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table" id="table">
                <thead>
                  <tr>
                    <th>a</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </section>
      <!-- Basic Tables end -->

@endsection

@push('custom-script')
      <script src="assets\extensions\jquery\jquery.min.js"></script>
      <script src="assets\extensions\datatables.net\js\jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets\extensions\datatables.net-bs5\js\dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
    <script>
        $(function() {
            let jquery_datatable = $("#tabel").DataTable({
                processing: true,
                serverSide: true,
                language: {
                    processing: `<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                    <span class="sr-only">Loading</span>`,
                },
                ajax: "{{ url('api/get-api-testing') }}",
                columns: [
                    {
                        data: "DT_RowIndex",
                        title: "No"
                    },
                    {
                      data: "name",
                      title: "Pelanggaran"
                    },
                    {
                        data: "score",
                        title: 'Poin'
                    },
                    {
                        data: "action",
                        title: 'Aksi',
                        orderable: false,
                        searchable: false,
                    },
                ],
            });
        });
    </script>

@endpush

@push('custom-style')
<link href="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css" />
@endpush