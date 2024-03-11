<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\CyroSku;
use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SyncProductShopifyCyro implements ShouldQueue
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
     *
     * @return void
     */
    public function handle()
    {
        $data=$this->data;

        $log=new Log();
        $log->url='iniciou_job_sync_product_Shopify_Cyro';
        $log->save();

/* 
        $tray_variant = Http::withHeaders([
            'Content-Type' => 'application/json',
            ])->get('https://www.griftrinetricot.com.br/web_api/products/variants/'.$data['variant_id'].'?access_token='.$data['access_token'],  []);
    
            $response_tray_variant=json_decode($tray_variant,true);

            */
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://www.griftrinetricot.com.br/web_api/products/variants/".$data['variant_id'].'?access_token='.$data['access_token'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => [
    "User-Agent: insomnia/2023.5.8"
  ],
]);

$tray_variant = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$response_tray_variant=json_decode($tray_variant,true);

            $log= new Log();
            $log->url= 'get_tray_variant: '.$data['variant_id'];
            $log->request=json_encode([]);
            $log->response=json_encode($response_tray_variant);
            $log->save();

            $sku=CyroSku::where('sku',$data['variant_id'])->first();

            if($sku){

                $estoque_tray=$response_tray_variant['Variant']['stock'];

                $shopify_variant = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token'=>'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'
                    ])->get('https://loja-atelie-tricot.myshopify.com/admin/api/2024-01/variants/'.$sku->variant_id.'.json',  []);
            
                    $response_shopify_variant=json_decode($shopify_variant,true);

                    $log= new Log();
                    $log->url= 'shopify_variant: '.$sku->variant_id;
                    $log->request=json_encode([]);
                    $log->response=json_encode($response_shopify_variant);
                    $log->save();


                
                $payload_connect_location=[
                    "location_id"=>55172595874,
                    "inventory_item_id"=>$response_shopify_variant['variant']['inventory_item_id']
                ];


                $connect_location = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'X-Shopify-Access-Token'=>'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'
                        ])->post('https://loja-atelie-tricot.myshopify.com/admin/api/2024-01/inventory_levels/connect.json',  $payload_connect_location);
                
                $response_connect_location=json_decode($connect_location,true);

                $log= new Log();
                    $log->url= 'connect_location';
                    $log->request=json_encode($payload_connect_location);
                    $log->response=json_encode($response_connect_location);
                    $log->save();



                $payload_adjust=[
                    "location_id"=>55172595874,
                    "inventory_item_id"=>$response_shopify_variant['variant']['inventory_item_id'],
                    "available"=>(int)$estoque_tray
                ];


                $ajust = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'X-Shopify-Access-Token'=>'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'
                        ])->post('https://loja-atelie-tricot.myshopify.com/admin/api/2024-01/inventory_levels/set.json',  $payload_adjust);
                
                $response_adjust=json_decode($ajust,true);

                $log= new Log();
                    $log->url= 'ajust';
                    $log->request=json_encode($payload_adjust);
                    $log->response=json_encode($response_adjust);
                    $log->save();



                

            }




    }
}
