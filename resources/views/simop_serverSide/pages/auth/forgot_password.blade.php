@extends('simop_serverSide/_layout_auth')

@section('content')
<!--start content-->
<main class="authentication-content">
    <div class="container-fluid">
        <div class="authentication-card">
            <div class="card shadow rounded-0 overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center border-end">
                        <img src="{{asset('template_serverSide/assets/images/error/forgot-password-frent-img.jpg')}}"
                            class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-6">
                        <div class="card-body p-4 p-sm-5">
                            <h5 class="card-title">Esqueceu sua senha?</h5>
                            <p class="card-text mb-5">Digite seu e-mail cadastrado para redefinir a senha</p>
                            <form class="form-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="inputEmailid" class="form-label">Email</label>
                                        <input type="email" class="form-control form-control-lg radius-30"
                                            id="inputEmailid" placeholder="Email">
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid gap-3">
                                            <button type="submit"
                                                class="btn btn-lg btn-primary radius-30">Enviar</button>
                                            <a href="/auth/sign_in" class="btn btn-lg btn-light radius-30">Voltar para
                                                Login</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end content-->
@endsection
