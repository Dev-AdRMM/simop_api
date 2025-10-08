@extends('simop_serverSide/_layout')

<!-- CSS -->
<link href="{{asset('template_serverSide/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />

<!-- JS -->
<script src="{{asset('template_serverSide/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('template_serverSide/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('template_serverSide/assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>

@section('content')
    <!--start content-->
    <main class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Mkesh</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Transações</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary">Nova Transação</button>
                    <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> 
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#debitRequestModal">Debit request</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#debitStatusModal">Debit Status</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:;">Something else here</a>
                    </div>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-header py-3">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6 me-auto">
                        <div class="ms-auto position-relative">
                            <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i
                                    class="bi bi-search"></i></div>
                            <input class="form-control ps-5" type="text" placeholder="Search Payment">
                        </div>
                    </div>
                    <div class="col-lg-2 col-6 col-md-3">
                        <select class="form-select">
                            <option>Status</option>
                            <option>Active</option>
                            <option>Disabled</option>
                            <option>Pending</option>
                            <option>Show All</option>
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
                    <table id="mkeshTransactionsTable" class="table align-middle mb-0">
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

            </div>
        </div>

    </main>
    <!--end page main-->

    <!-- Modal debit request-->
    <div class="col">
        <div class="modal fade" id="debitRequestModal" tabindex="-1" aria-labelledby="debitRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="mb-0 text-uppercase" id="debitRequestModalLabel">Mkesh - Debit Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="debitForm" class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Número Mkesh</label>
                                <input type="number" id="msisdn" class="form-control" placeholder="Ex: 25884XXXXXXX" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Valor (MT)</label>
                                <input type="number" id="amount" class="form-control" min="1" required>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Enviar Débito</button>
                                </div>
                            </div>
                        </form>

                        <div id="debitResponse" class="mt-3"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--end Modal debit request-->

    <!-- Modal debit status-->
    <div class="col">
        <div class="modal fade" id="debitStatusModal" tabindex="-1" aria-labelledby="debitStatusModalModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="mb-0 text-uppercase" id="debitStatusModalModalLabel">Mkesh - Debit Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="debitStatusForm" class="row g-3">
                            <div class="col-12">
                                <label class="form-label">ID da transação Mkesh</label>
                                <input type="number" id="transaction_id" class="form-control" placeholder="Ex: ADR000027" required>
                            </div>

                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Ver o Status</button>
                                </div>
                            </div>
                        </form>

                        <div id="debitStatusResponse" class="mt-3"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--end Modal debit request-->

@endsection

@section('scripts')
<script>
    const mkeshTransactionsDataUrl = "{{ route('transactions.data') }}";
    const mkeshImage = "{{ asset('template_serverSide/assets/images/avatars/mkesh.png') }}";

</script>

<script src="{{ asset('js/transactions/mkesh.js') }}"></script>
@endsection
