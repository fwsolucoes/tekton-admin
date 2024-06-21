<?php

namespace App\Http\Controllers;


namespace App\Http\Controllers\Catalogo;

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

class ExpressionsController extends Controller
{
    

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function catalogo(Request $request)
    {

        $data= json_decode($request->getContent(),true);
     
        $numero_parcelas=(int) str_replace(' Parcelas','',$data['parcelas']);

        

        if($numero_parcelas==12){
            $valor_parcelas=((int)$data['price']-(int)$data['price']*0.3 +4000)*0.065 ;
        }

        if($numero_parcelas==24){
            $valor_parcelas=((int)$data['price']-(int)$data['price']*0.3 +4000)*0.075 ;
        }
        if($numero_parcelas==36){
            $valor_parcelas=((int)$data['price']-(int)$data['price']*0.3 +4000)*0.085 ;
        }
        

       

            $message='Olá {{nome}}, tudo bem?😀! {{breakline}}{{breakline}}Aqui é Rafael, do site Meu Primeiro Caminhão! Segue abaixo o resultado de sua simulação{{breakline}}{{breakline}}
            *Nome*: '.$data['nome'].' {{breakline}}
            *Telefone*: '.$data['phone'].'{{breakline}}
            *Modelo de Veículo*: '.$data['product'].'{{breakline}}
            *Número de Parcelas*: '.$data['parcelas'].'{{breakline}}
            *Valor de cada Parcela*: R$ '.number_format($valor_parcelas,2,",",".").'{{breakline}}{{breakline}}
            
            Em breve um de nossos especialistas entrará em contato para lhe passar mais detalhes e informações! {{breakline}}{{breakline}}
            Qualquer dúvida estamos à sua disposição!{{breakline}}{{breakline}}Atenciosamente,{{breakline}}*Equipe Meu Primeiro Caminhão*';
         
            $content=str_replace('{{nome}}',$data['nome'],$message);
     
            $phone=str_replace([' ','(',')','-','+'],'',$data['phone']);
         

            $payload_whatsapp= [
                "number" => $phone,
                "openTicket"=>"1",
                "queueId"=>"29",
                "body"=>$content
            ]; 


            $send_whatsapp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 7240e86e-240f-4d30-8626-7fc9be84f2db'
            ])->post('https://api.zapychat.com/api/messages/send',  $payload_whatsapp);

            $response_whatsapp=json_decode($send_whatsapp,true);


           

            
            


        
                   

       
    }


}
