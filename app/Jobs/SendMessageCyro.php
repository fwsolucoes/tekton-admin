<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CyroMessage;
use Illuminate\Support\Facades\Http;
use App\Models\Log;

class SendMessageCyro implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    /**
    * Create a new job instance.
    *
    * @return void
    */
  public function __construct($data)
  {
      $this->data=$data;
  }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data=$this->data;

        if($data["event"]=="abandoned.created"){

            $message=CyroMessage::find(1);
            $message=$message->message;
         
            $content=str_replace('{{nome}}',$data['nome'],$message);
            $content=str_replace('{{link}}',$data['link'],$content);
            $phone=str_replace([' ','(',')','-','+'],'',$data['phone']);
         

            $payload_whatsapp= [
            "numbers" => [
                $phone
            ], 
            "textMessage" => [
                    "text" => $content
                ] 
            ]; 

            $send_whatsapp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'apikey' => '601EA470-AF83-4AF2-BB9A-93552C32E380'
            ])->post('https://api.cloudzapi.com/zzJwtOWJ4d/message/sendText',  $payload_whatsapp);

            $response_whatsapp=json_decode($send_whatsapp,true);

            $log= new Log();
            $log->url= 'mensagem_whatsapp1';
            $log->request=json_encode($payload_whatsapp);
            $log->response=json_encode($response_whatsapp);
            $log->save();

        }

        if($data["event"]=="order.paid"){

            $message=CyroMessage::find(2);
            $message=$message->message;
            $content=str_replace('{{nome}}',$data['nome'],$message);

            $phone=str_replace([' ','(',')','-','+'],'',$data['phone']);
          

            $payload_whatsapp= [
            "numbers" => [
                $phone
            ], 
            "textMessage" => [
                    "text" => $content
                ] 
            ]; 

            $send_whatsapp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'apikey' => '601EA470-AF83-4AF2-BB9A-93552C32E380'
            ])->post('https://api.cloudzapi.com/zzJwtOWJ4d/message/sendText',  $payload_whatsapp);

            $response_whatsapp=json_decode($send_whatsapp,true);

            $log= new Log();
            $log->url= 'mensagem_whatsapp2';
            $log->request=json_encode($payload_whatsapp);
            $log->response=json_encode($response_whatsapp);
            $log->save();

        }

        if($data["event"]=="order.updated" && $data["webhook"]["order"]["status_id"]=="Fulfilled"){

            $message=CyroMessage::find(3);
            $message=$message->message;
            $content=str_replace('{{nome}}',$data['nome'],$message);
            $content=str_replace('{{code}}',$data['webhook']['order']['tracking_number'],$content);
            $content=str_replace('{{link_rastreio}}',$data['webhook']['order']['fulfillments'][0]['tracking_url'],$content);
            $phone=str_replace([' ','(',')','-','+'],'',$data['phone']);
            

            $payload_whatsapp= [
            "numbers" => [
                $phone
            ], 
            "textMessage" => [
                    "text" => $content
                ] 
            ]; 

            $send_whatsapp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'apikey' => '601EA470-AF83-4AF2-BB9A-93552C32E380'
            ])->post('https://api.cloudzapi.com/zzJwtOWJ4d/message/sendText',  $payload_whatsapp);

            $response_whatsapp=json_decode($send_whatsapp,true);

            $log= new Log();
            $log->url= 'mensagem_whatsapp3';
            $log->request=json_encode($payload_whatsapp);
            $log->response=json_encode($response_whatsapp);
            $log->save();

        }

        
    }
}
