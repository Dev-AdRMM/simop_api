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
                            <table id="transactionsTable" class="table align-middle mb-0">
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
            </div>

        </div>
    <!--end row-->

    </main>
    <!--end page main-->
@endsection

@section('scripts')
<script>
    const transactionsDataUrl = "{{ route('transactions.data') }}";
    const walletImages = {
        mkesh: "{{ asset('template_serverSide/assets/images/avatars/mkesh.png') }}",
        mpesa: "{{ asset('template_serverSide/assets/images/avatars/mpesa.png') }}",
        emola: "{{ asset('template_serverSide/assets/images/avatars/emola.png') }}"
    };
</script>

<script src="{{ asset('js/transactions/index.js') }}"></script>
@endsection