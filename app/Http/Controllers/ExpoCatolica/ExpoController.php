<?php

namespace App\Http\Controllers\ExpoCatolica;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use App\Models\Plan;
use PDF; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class ExpoController extends Controller
{
    

   


     /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function formulario(Request $request)
    {

        $data= json_decode($request->getContent(),true);
     
             

            $message='Olá {{nome}}, tudo bem?😀! {{breakline}}{{breakline}}Recebemos os seus dados para participar do Sorteio da *Sancton* na ExpoCatólica! {{breakline}}{{breakline}}O Sorteio acontecerá no domingo, dia 07/07, às 18hs. Obrigado por participar! Qualquer dúvida estamos a disposição! {{breakline}}{{breakline}}Atenciosamente, {{breakline}}{{breakline}}*Equipe Sancton - Tecnologias para a Evangelização*';
         
            $content=str_replace('{{nome}}',$data['nome'],$message);
     
            $phone=str_replace([' ','(',')','-','+'],'',$data['phone']);
         

            $payload_whatsapp= [
                "number" => $phone,
                "openTicket"=>"1",
                "queueId"=>"12",
                "body"=>$content
            ]; 


            $send_whatsapp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer sanctonsaojose'
            ])->post('https://apichat.sancton.com.br/api/messages/send',  $payload_whatsapp);

            $response_whatsapp=json_decode($send_whatsapp,true);


           

            
            


        
                   

       
    }


}
