<?php

namespace App\Http\Controllers\Iouvidor;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Ocorrencias;
use App\Models\UnitrinusList;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



class IouvidorController extends Controller
{
    var $docFile  = ''; 
    var $title    = ''; 
    var $htmlHead = ''; 
    var $htmlBody = ''; 
    /** 
     * Constructor 
     * 
     * @return void 
     */ 
    function __construct(){ 
        $this->title = ''; 
        $this->htmlHead = ''; 
        $this->htmlBody = ''; 
    } 

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function fluxo_webhook(Request $request)
    {


        $webhook= json_decode($request->getContent(),true);

        $log=new Log();
        $log->url='webhook_fluxo';
        $log->request=json_encode($webhook);
        $log->save();

        $ocorrencia= new Ocorrencias();
        $ocorrencia->nome=$webhook['nome'];
        $ocorrencia->descricao=$webhook['descricao'];
        $ocorrencia->data=Carbon::now();
        $ocorrencia->id_entidade=3;
        $ocorrencia->codigo='2024-S-'.rand(5);
        $ocorrencia->id_usuario=100;
        $ocorrencia->id_tipo=$webhook['tipo'];
        $ocorrencia->anonimo=0;
        $ocorrencia->aceito=0;
        $ocorrencia->prazo=30;
        $ocorrencia->prazo2=30;
        $ocorrencia->save();

         
        
       
       

        return response()->json([
            'ok' =>'ok' 
        ]);
         
    }



}
