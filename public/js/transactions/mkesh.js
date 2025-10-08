document.addEventListener("DOMContentLoaded", function () {
    const debitForm = document.getElementById("debitRequestForm");
    const responseDiv = document.getElementById("debitRequestResponse");

    debitForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        // Gera transaction_id simples
        const transactionId = "ADR" + Math.floor(100000 + Math.random() * 900000);

        const payload = {
            msisdn: document.getElementById("msisdn").value,
            amount: parseFloat(document.getElementById("amount").value),
            transaction_id: transactionId
        };

        responseDiv.innerHTML = `<div class="alert alert-info">Processando requisição...</div>`;

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

            responseDiv.innerHTML = `
                <div class="alert alert-success">
                    <strong>Transação enviada!</strong><br>
                    Transaction ID: ${transactionId}<br>
                    <pre>${JSON.stringify(result, null, 2)}</pre>
                    <button class="btn btn-sm btn-outline-primary mt-2" onclick="checkTransactionStatus('${transactionId}')">Verificar Status</button>
                </div>
            `;
        } catch (error) {
            console.error(error);
            responseDiv.innerHTML = `<div class="alert alert-danger">Erro ao processar o débito.</div>`;
        }
    });
});

// Função global para verificar status
async function checkTransactionStatus(transactionId) {
    const responseDiv = document.getElementById("debitRequestResponse");
    responseDiv.innerHTML = `<div class="alert alert-info">Consultando status da transação...</div>`;

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

        responseDiv.innerHTML = `
            <div class="alert alert-warning">
                <strong>Status da Transação:</strong> ${result.status}<br>
                <pre>${JSON.stringify(result, null, 2)}</pre>
            </div>
        `;
    } catch (error) {
        console.error(error);
        responseDiv.innerHTML = `<div class="alert alert-danger">Erro ao consultar status.</div>`;
    }
}

// mkesh Transations Table
$(document).ready(function() {
    $('#mkeshTransactionsTable').DataTable({
        processing: true,
        serverSide: false, 
        ajax: {
            url: mkeshTransactionsDataUrl, // variável vinda do Blade
            dataSrc: 'data' // 👈 importante!
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
});

// Debit Status
$(document).ready(function () {
    // 🔹 Submeter o formulário para ver status
    $('#debitStatusForm').on('submit', function (e) {
        e.preventDefault();

        const transactionId = $('#transaction_id').val().trim();
        if (!transactionId) {
            alert('Por favor, insira o ID da transação Mkesh.');
            return;
        }

        // Mostrar loading
        $('#debitStatusResponse').html('<div class="text-center text-muted">A consultar status...</div>');

        // Chamada AJAX para tua rota Laravel
        $.ajax({
            url: '/api/v1/mkesh/debit_status',
            method: 'POST',
            data: {
                transaction_id: transactionId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                let formattedResponse = '';

                if (typeof response === 'object') {
                    formattedResponse = `<pre class="bg-light p-3 rounded"><code>${JSON.stringify(response, null, 2)}</code></pre>`;
                } else {
                    // Se for XML
                    formattedResponse = `<pre class="bg-light p-3 rounded"><code>${response}</code></pre>`;
                }

                $('#debitStatusResponse').html(formattedResponse);
            },
            error: function (xhr) {
                $('#debitStatusResponse').html(`
                    <div class="alert alert-danger mt-2">
                        Ocorreu um erro ao consultar o status.<br>
                        <strong>${xhr.status}</strong> - ${xhr.statusText}
                    </div>
                `);
            }
        });
    });
});
