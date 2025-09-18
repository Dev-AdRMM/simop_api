@extends('simop_serverSide/_layout')

<script src="{{ asset('/js/vendor/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('/js/Server_Side/Supplier/Categories.ajax.js') }}"></script>


@section('content')

<main class="page-content">
  <!--breadcrumb-->
  <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">eCommerce</div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Categories</li>
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

  <div class="card">
    <div class="card-header py-3">
      <h6 class="mb-0">Adicionar Categoria do Fornecedor</h6>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-lg-4 d-flex">
          <div class="card border shadow-none w-100">
            <div class="card-body">
              <form class="row g-3">
                <div class="col-12">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" id="name" placeholder="Nome da Categoria">
                </div>
                <div class="col-12">
                  <label class="form-label">Descrição</label>
                  <textarea class="form-control" id="description" rows="3" cols="3" placeholder="Descrição da Categoria"></textarea>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn btn-primary" id="add_category">Adicionar Categoria</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-8 d-flex">
          <div class="card border shadow-none w-100">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead class="table-light">
                    <tr>
                      <th><input class="form-check-input" type="checkbox"></th>
                      <th>ID</th>
                      <th>NOME</th>
                      <th>DESCRIÇÃO</th>
                      <th>ACÇÃO</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Os dados serão inseridos aqui dinamicamente -->
                  </tbody>
                </table>
              </div>

              <nav class="float-end mt-0" aria-label="Page navigation">
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
      </div><!--end row-->
    </div>
  </div>

</main>

@endsections