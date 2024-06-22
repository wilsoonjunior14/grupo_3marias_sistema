<?php

namespace App\Http\Controllers;

use App\Business\BillReceiveBusiness;
use App\Business\BillTicketBusiness;
use App\Business\ClientBusiness;
use App\Business\ContractBusiness;
use App\Business\EnterpriseBusiness;
use App\Business\EnterpriseOwnerBusiness;
use App\Business\ProposalBusiness;
use App\Models\BillTicket;
use App\Models\ClientTicketView;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function getClientDataPDF(Request $request, $id) {

        $clientBusiness = new ClientBusiness();
        $client = $clientBusiness->getById(id: $id);

        $data = [
            'title' => 'Ficha de Cadastro do Cliente',
            'client' => $client
        ];
        return view('client-data-pdf', $data);
    }

    public function getProposalPDF(Request $request, $id) {
        $proposalBusiness = new ProposalBusiness();
        $proposal = $proposalBusiness->getById(id: $id);
        
        $enterpriseBusiness = new EnterpriseBusiness();
        $enterprise = $enterpriseBusiness->getById(id: 1, mergeFields: true);

        $data = [
            'title' => 'Proposta',
            'global_value' => number_format($proposal->global_value, 2, ',', '.'),
            'proposal' => $proposal,
            'enterprise' => $enterprise
        ];
        return view('proposal-pdf', $data);
    }

    public function getContractPDF(Request $request, $id) {
        $contractBusiness = new ContractBusiness();
        $contract = $contractBusiness->getById(id: $id);

        $enterpriseBusiness = new EnterpriseBusiness();
        $enterprise = $enterpriseBusiness->getById(id: 1, mergeFields: true);

        $enterpriseOwnerBusiness = new EnterpriseOwnerBusiness();
        $owner = $enterpriseOwnerBusiness->getById(id: 1);

        $data = [
            'title' => 'CONTRATO DE EMPREITADA POR PREÇO GLOBAL COM FORNECIMENTO DE PROJETOS E CONSTRUÇÃO DA OBRA',
            'contract' => $contract,
            'enterprise' => $enterprise,
            'owner' => $owner
        ];
        return view('contract-pdf', $data);
    }

    public function getAlvaraPDF(Request $request, $id) {
        $contractBusiness = new ContractBusiness();
        $contract = $contractBusiness->getById(id: $id);

        $data = [
            'title' => 'REQUERIMENTO',
            'contract' => $contract
        ];
        return view('alvara-pdf', $data);
    }

    public function getRecibo(Request $request, $id) {
        $ticket = (new BillTicketBusiness())->getById(id: $id);
        $billReceive = (new BillReceiveBusiness())->getById(id: $ticket->bill_receive_id);
        $contract = (new ContractBusiness())->getById(id: $billReceive->contract_id, mergeFields: true);
        $proposal = (new ProposalBusiness())->getById(id: $contract->proposal_id, mergeFields: false);
        $client = (new ClientBusiness())->getById(id: $proposal->client_id, mergeFields: false);
        $enterprise = (new EnterpriseBusiness())->getById(id: 1, mergeFields: false);

        $data = [
            'title' => "Recibo de Pagamento Nº " . $id,
            'client' => $client,
            'ticket' => $ticket,
            'contract' => $contract,
            'enterprise' => $enterprise
        ];
        return view('recibo-pdf', $data);
    }
}
