// Função global para verificar status com spinner e cores
async function checkTransactionStatus(transactionId, responseDivId = "debitRequestResponse") {
    const responseDiv = document.getElementById(responseDivId);
    responseDiv.innerHTML = `
        <div class="d-flex align-items-center text-info">
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
            Consultando status da transação...
        </div>
    `;

    try {
        const response = await fetch("/api/v1/mkesh/debit_status", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({ transaction_id: transactionId })
        });

        const result = await response.json();
        const status = (result.status || '').toUpperCase();

        // Define cor do badge
        let badgeClass = 'secondary';
        if (status === 'SUCCESS' || status === 'SUCCESSFUL') badgeClass = 'success';
        else if (status === 'FAILED') badgeClass = 'danger';
        else if (status === 'SENT') badgeClass = 'warning';
        else if (status === 'CHECKED') badgeClass = 'info';

        responseDiv.innerHTML = `
            <div class="alert alert-light d-flex flex-column">
                <strong>Status da Transação:</strong> 
                <span class="badge rounded-pill bg-${badgeClass}">${status}</span>
                <pre class="mt-2">${JSON.stringify(result, null, 2)}</pre>
            </div>
        `;
    } catch (error) {
        console.error(error);
        responseDiv.innerHTML = `<div class="alert alert-danger">Erro ao consultar status.</div>`;
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // 🔹 Formulário Debit Request
    const debitRequestForm = document.getElementById("debitRequestForm");
    const debitRequestResponseDiv = document.getElementById("debitRequestResponse");

    debitRequestForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const transactionId = "ADR" + Math.floor(100000 + Math.random() * 900000);
        const payload = {
            msisdn: document.getElementById("msisdn").value,
            amount: parseFloat(document.getElementById("amount").value),
            transaction_id: transactionId
        };

        debitRequestResponseDiv.innerHTML = `
            <div class="d-flex align-items-center text-info">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                Processando requisição...
            </div>
        `;

        try {
            const response = await fetch("/api/v1/mkesh/debit_request", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(payload)
            });

            let result;
            const contentType = response.headers.get("content-type");

            if (contentType && contentType.includes("application/json")) {
                result = await response.json();
            } else {
                result = await response.text();
            }

            debitRequestResponseDiv.innerHTML = `
                <div class="alert alert-light d-flex flex-column">
                    <strong>Transação enviada!</strong><br>
                    Transaction ID: ${transactionId}
                    <pre class="mt-2">${JSON.stringify(result, null, 2)}</pre>
                    <button class="btn btn-sm btn-outline-primary mt-2" 
                        onclick="checkTransactionStatus('${transactionId}', 'debitRequestResponse')">
                        Verificar Status
                    </button>
                </div>
            `;
        } catch (error) {
            console.error(error);
            debitRequestResponseDiv.innerHTML = `<div class="alert alert-danger">Erro ao processar o débito.</div>`;
        }
    });

    // 🔹 Formulário Debit Status
    const debitStatusForm = document.getElementById("debitStatusForm");
    const debitStatusResponseDiv = document.getElementById("debitStatusResponse");

    debitStatusForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const transactionId = document.getElementById("transaction_id").value.trim();
        if (!transactionId) {
            alert('Por favor, insira o ID da transação Mkesh.');
            return;
        }

        debitStatusResponseDiv.innerHTML = `
            <div class="d-flex align-items-center text-info">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                A consultar status...
            </div>
        `;

        checkTransactionStatus(transactionId, 'debitStatusResponse');
    });
});

// Inicializa DataTable Mkesh Transactions
$(document).ready(function() {
    const table = $('#mkeshTransactionsTable').DataTable({
        processing: true,
        serverSide: false, 
        dom: 'rtip', // desativa search box e length padrão
        ajax: {
            url: mkeshTransactionsDataUrl, // variável vinda do Blade
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            {
                data: 'wallet',
                render: function () {
                    return `
                        <div class="d-flex align-items-center gap-3 cursor-pointer">
                            <img src="${mkeshImage}" class="rounded-circle" width="30" height="30" alt="Mkesh">
                            <div>
                                <p class="mb-0">Mkesh</p>
                            </div>
                        </div>
                    `;
                }
            },
            { data: 'msisdn' },
            { data: 'transaction_id' },
            { data: 'amount' },
            {
                data: 'status',
                render: function (data) {
                    let badgeClass = 'bg-secondary';
                    if (data.toLowerCase() === 'successful') badgeClass = 'bg-success';
                    else if (data.toLowerCase() === 'failed') badgeClass = 'bg-danger';
                    else if (data.toLowerCase() === 'sent') badgeClass = 'bg-warning text-dark';
                    else if (data.toLowerCase() === 'checked') badgeClass = 'bg-info text-dark';

                    return `<span class="badge ${badgeClass}">${data.toUpperCase()}</span>`;
                }
            },
            {
                data: 'provider_response',
                render: function(data) {
                    return data ? data : '-';
                }
            },
            {
                data: 'created_at',
                render: function (data) {
                    const d = new Date(data);
                    return d.toLocaleString('pt-PT');
                }
            },
            {
                data: 'updated_at',
                render: function (data) {
                    const d = new Date(data);
                    return d.toLocaleString('pt-PT');
                }
            },
            {
                data: null,
                orderable: false,
                render: function(row) {
                    return `
                        <a href="/transactions/${row.id}" class="text-primary" title="Ver detalhe">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    `;
                }
            }
        ]
    });

    // 🔹 Pesquisa customizada
    $('.card-header input[type="text"]').on('keyup', function() {
        table.search(this.value).draw();
    });

    // 🔹 Filtro de Status
    $('.card-header select.form-select').first().on('change', function() {
        const val = this.value;
        if (val === "Show All" || val === "Status") {
            table.column(5).search('').draw(); // coluna 5 = status
        } else {
            table.column(5).search(val, true, false).draw();
        }
    });

    // 🔹 Seleção de quantidade de linhas
    $('.card-header select.form-select').last().on('change', function() {
        const len = parseInt(this.value.replace('Show ', ''));
        table.page.len(len).draw();
    });
});
