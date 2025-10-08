$(document).ready(function() {
    $('#transactionsTable').DataTable({
        processing: true,
        serverSide: false, 
        ajax: {
            url: transactionsDataUrl, // variável vinda do Blade
            dataSrc: 'data' // 👈 importante!
        },
        columns: [
            { data: 'id' },
            {
                data: 'wallet',
                render: function (data, type, row) {
                    const image = walletImages[data] || walletImages['mkesh']; // imagem padrão
                    const walletName = data.charAt(0).toUpperCase() + data.slice(1); // primeira letra maiúscula
                    return `
                        <div class="d-flex align-items-center gap-3 cursor-pointer">
                            <img src="${image}" class="rounded-circle" width="30" height="30" alt="${walletName}">
                            <div>
                                <p class="mb-0">${walletName}</p>
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