<?php


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
            $valor_parcelas=((int)$data['price']-(int)$data['price']*0.3 +4000)*0.085 ;
        }

        if($numero_parcelas==24){
            $valor_parcelas=((int)$data['price']-(int)$data['price']*0.3 +4000)*0.085 ;
        }
        if($numero_parcelas==36){
            $valor_parcelas=((int)$data['price']-(int)$data['price']*0.3 +4000)*0.075 ;
        }

        if($numero_parcelas==48){
            $valor_parcelas=((int)$data['price']-(int)$data['price']*0.3 +4000)*0.065 ;
        }
        

       

            $message='Olá {{nome}}, tudo bem?😀! {{breakline}}{{breakline}}Aqui é Rafael, do site Meu Primeiro Caminhão! Segue abaixo o resultado de sua simulação{{breakline}}{{breakline}}
*Nome*: '.$data['nome'].' {{breakline}}
*Telefone*: '.$data['phone'].'{{breakline}}*Modelo de Veículo*: '.$data['product'].'{{breakline}}*Valor do Veículo*: R$ '.number_format($data['price'],2,",",".").'{{breakline}}*Valor da Entrada*: R$ '.number_format($data['price']*0.3,2,",",".").'{{breakline}}*Número de Parcelas*: '.$data['parcelas'].'{{breakline}}*Valor de cada Parcela*: R$ '.number_format($valor_parcelas,2,",",".").'{{breakline}}{{breakline}}Em breve um de nossos especialistas entrará em contato para lhe passar mais detalhes e informações! {{breakline}}{{breakline}}
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


     /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function formulario(Request $request)
    {

        $data= json_decode($request->getContent(),true);
     
             

            $message='Olá {{nome}}, tudo bem?😀! {{breakline}}{{breakline}}Recebemos os seus dados preenchidos em nossa página, no site Meu Primeiro Caminhão!{{breakline}}{{breakline}}*1) Nome*: '.$data['Nome_completo']['first_name'].' {{breakline}}*2) Telefone*: '.$data['phone'].'{{breakline}}*3) Você possuí algum valor para dar como parte no negócio?*: '.$data['dropdown'].'{{breakline}}*4) Você possuí algum veículo para dar como parte no negócio?*: '.$data['dropdown_1'].'{{breakline}}*5) Você precisa de um caminhão para agora (de forma imediata)?*: '.$data['dropdown_2'].'{{breakline}}*6) Qual é o valor do caminhão que você está buscando?*: '.$data['dropdown_3'].'{{breakline}}*7) Você sabe qual veículo quer?*: '.$data['dropdown_4'].'{{breakline}}*8) A partir de qual ano precisa ser esse veículo?*: '.$data['dropdown_5'].'{{breakline}}*9) Qual implemento? *: '.$data['dropdown_6'].'{{breakline}}*10) Você sabe onde vai agregar?*: '.$data['dropdown_7'].'{{breakline}}*11) Quanto vai ganhar por mês?*: '.$data['dropdown_8'].'{{breakline}}*12) Que tipo de carga vai carregar?*: '.$data['dropdown_9'].'{{breakline}}*13) Se fecharmos negócio hoje, quando você começa a carregar?*: '.$data['dropdown_10'].'{{breakline}}Em breve um de nossos especialistas entrará em contato para lhe passar mais detalhes e informações! {{breakline}}{{breakline}}Qualquer dúvida estamos à sua disposição!{{breakline}}{{breakline}}Atenciosamente,{{breakline}}*Equipe Meu Primeiro Caminhão*';
         
            $content=str_replace('{{nome}}',$data['Nome_completo']['first_name'],$message);
     
            $phone=str_replace([' ','(',')','-','+'],'',$data['phone']);
         

            $payload_whatsapp= [
                "number" => $phone,
                "openTicket"=>"1",
                "queueId"=>"30",
                "body"=>$content
            ]; 


            $send_whatsapp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 7240e86e-240f-4d30-8626-7fc9be84f2db'
            ])->post('https://api.zapychat.com/api/messages/send',  $payload_whatsapp);

            $response_whatsapp=json_decode($send_whatsapp,true);


           

            
            


        
                   

       
    }


}
