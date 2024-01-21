<?php

namespace App\Http\Controllers;

use App\Business\ClientBusiness;
use App\Business\EnterpriseBusiness;
use App\Business\ProposalBusiness;
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
        $enterprise = $enterpriseBusiness->getById(id: 1);

        $data = [
            'title' => 'Proposta',
            'global_value' => number_format($proposal->global_value, 2, ',', '.'),
            'proposal' => $proposal,
            'enterprise' => $enterprise
        ];
        return view('proposal-pdf', $data);
    }
}
