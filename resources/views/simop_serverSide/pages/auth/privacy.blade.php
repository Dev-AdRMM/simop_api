@extends('simop_serverSide/_layout_auth')
   
@section('content')    
       <!--start content-->
       <main class="authentication-content">
        <div class="container">
          <div class="mt-4">
            <div class="card rounded-0 overflow-hidden shadow-none bg-white border">
                <div class="card-body">
                  <h5 class="card-title">Baisc Accordion</h5>
                  <div class="my-3 border-top"></div>
                  <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          Quem somos
                        </button>
                      </h2>
                      <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <strong>A Dimark</strong>  é uma empresa líder em serviços de consultoria em tecnologia da informação, 
                          desenvolvimento de aplicações web e sistemas informáticos. Combinando nossa experiência e conhecimento 
                          especializado em <code>marketing digital e publicidade,</code> ajudamos nossos clientes a alcançar seus objetivos 
                          empresariais e maximizar seu potencial online.
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          Como contactar-nos
                        </button>
                      </h2>
                      <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          A sua opinião é importante para nós, se tiver alguma dúvida sobre a nossa política de privacidade, pode enviar-nos um e-mail: privacy@dimark.co.mz.
                                Pode contactar o nosso responsável pela protecção de dados em privacy@dimark.co.mz.
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                          Os nossos princípios
                        </button>
                      </h2>
                      <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          A Dimark está empenhada em respeitar a sua privacidade. Levamos a sério a privacidade, a segurança e o cumprimento das leis de protecção de dados.
                          Definimos os nossos principais compromissos em matéria de privacidade no nosso Centro de Privacidade. O nosso objectivo é colocar estes compromissos 
                          no centro de tudo o que fazemos.
                        </div>
                      </div>
                    </div>
      
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFor" aria-expanded="false" aria-controls="collapseFor">
                         Como usamos suas informações pessoais e nossa base legal para fazê-lo
                        </button>
                      </h2>
                      <div id="collapseFor" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                      </div>
                    </div>
                    
                  </div>
                </div>       
            </div>
          </div>
        </div>
       </main>
@endsection

