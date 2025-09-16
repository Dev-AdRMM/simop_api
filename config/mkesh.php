<?php

return [
    'base_url' => 'https://41.220.193.151',
    'username' => 'ADR',
    'password' => 'Mozambique2025!',
    'endpoints' => [
        'debit' => '/DebitServlet/DebitSvlt',
        'status' => '/GetTransactionStatus/GetStatusSvlt',
        'sptransfer' => '/sptransfer/sptransfer',
    ],
];

#https://41.220.193.151/DebitServlet/DebitSvlt (inicia um débito (exemplo: cliente paga via mKesh))
#https://41.220.193.151/GetTransactionStatus/GetStatusSvlt (Verifica o estado de uma transação enviada)
#https://41.220.193.151/sptransfer/sptransfer (Usado para transferências entre contas (SP → cliente) )

