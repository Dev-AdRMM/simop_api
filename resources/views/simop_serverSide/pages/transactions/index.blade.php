@extends('simop_serverSide/_layout')

<!-- CSS -->
<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="{{ asset('/js/vendor/jquery-3.6.0.min.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<!-- JS -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

@section('content')
    <!--start content-->
    <main class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Transações</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Todas</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary">Settings</button>
                    <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item"
                            href="javascript:;">Action</a>
                        <a class="dropdown-item" href="javascript:;">Another action</a>
                        <a class="dropdown-item" href="javascript:;">Something else here</a>
                        <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
                    </div>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="">
                <div class="card w-100">
                    <div class="card-header py-3">
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6 me-auto">
                                <div class="ms-auto position-relative">
                                    <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i
                                            class="bi bi-search"></i></div>
                                    <input class="form-control ps-5" type="text" placeholder="search produts">
                                </div>
                            </div>
                            <div class="col-lg-2 col-6 col-md-3">
                                <select class="form-select">
                                    <option>Opçoes</option>
                                    <option>Opçoes 1</option>
                                    <option>Opçoes 2 </option>
                                    <option>Opçoes 3</option>
                                    <option>Opçoes 4</option>
                                    <option>Opçoes 5</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-6 col-md-3">
                                <select class="form-select">
                                    <option>Show 10</option>
                                    <option>Show 30</option>
                                    <option>Show 50</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="transactionsTable" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Carteira</th>
                                        <th>Contacto</th>
                                        <th>Id Transação</th>
                                        <th>Valor</th>
                                        <th>Estado</th>
                                        <th>Provider response</th>
                                        <th>Criado em</th>
                                        <th>Actualizado em</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <nav class="float-end" aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- <div class="col-12 col-lg-3 d-flex">
                <div class="card w-100">
                    <div class="card-header py-3">
                        <h5 class="mb-0">Filter by</h5>
                    </div>
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Order ID</label>
                                <input type="text" class="form-control" placeholder="Order ID">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Customer</label>
                                <input type="text" class="form-control" placeholder="Customer name">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Order Status</label>
                                <select class="form-select">
                                    <option>Published</option>
                                    <option>Draft</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Total</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Date Added</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Date Modified</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-primary">Filter Product</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
        </div><!--end row-->

    </main>
    <!--end page main-->
@endsection

<script>
$(document).ready(function() {
    $('#transactionsTable').DataTable({
        processing: true,
        serverSide: false, 
        ajax: {
            url: '{{ route("transactions.data") }}',
            dataSrc: 'data' // 👈 importante!
        },
        columns: [
            { data: 'id' },
            { data: 'wallet' },
            { data: 'msisdn' },
            { data: 'transaction_id' },
            { data: 'amount' },
            { 
                data: 'status',
                render: function(data) {
                    let badgeClass = 'secondary';
                    if (data.toLowerCase() === 'successful') badgeClass = 'success';
                    else if (data.toLowerCase() === 'failed') badgeClass = 'danger';
                    else if (data.toLowerCase() === 'sent') badgeClass = 'warning';
                    else if (data.toLowerCase() === 'checked') badgeClass = 'info';

                    return `<span class="badge rounded-pill bg-${badgeClass}">${data}</span>`;
                }
            },
            { 
                data: 'provider_response',
                render: function(data) {
                    if (!data) return '-';
                    if (data.includes('errorcode')) {
                        let match = data.match(/errorcode="([^"]+)"/);
                        return match ? match[1] : 'Erro';
                    }
                    return '-';
                }
            },
            { data: 'created_at' },
            { data: 'updated_at' },
            {
                data: null,
                render: function(row) {
                    return `
                        <div class="d-flex align-items-center gap-2">
                            <a href="/transactions/${row.id}" class="text-primary" title="Ver detalhe">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </div>
                    `;
                }
            }
        ]
    });
});

</script>