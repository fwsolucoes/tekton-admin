<?php

namespace App\Http\Controllers\Cyro;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Log;
use App\Models\CyroSku;
use App\Jobs\SyncProductShopifyCyro;
use App\Jobs\SendMessageCyro;

use Illuminate\Support\Facades\Http;

class CyroController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function tray_webhook(Request $request)
    {
        if($request->input('seller_id')=="1203091" && $request->input('scope_name')=="product_stock"){
            $webhook= json_decode($request->getContent(),true);
            $tray=[
                "scope_name"=>$request->input('scope_name'),
                "scope_id"=>$request->input('scope_id'),
                "seller_id"=>$request->input('seller_id'),
                "act"=>$request->input('act')
            ];
    
            $log=new Log();
            $log->url='webhook_tray';
            $log->request=json_encode($tray);
            $log->save();
    
            $payload_login_tray= [
                "consumer_key" => "35ce30851042b1bcac6b8eb3e2cee03d18466b0abf957501994f332d51db92ec",
                "consumer_secret" => "a27864ba09b78f1e105332024c9a21c0c4b9b088cfe7b2c4864943fb355e8b74",
                "code"=>"d2e2b9a75ee8aba7c99a4bee6b30a16ca9a4baed244d42e2efd03df9044ba482"
                ]; 
        
                $login_tray = Http::withHeaders([
                'Content-Type' => 'application/json',
                ])->post('https://www.griftrinetricot.com.br/web_api/auth',  $payload_login_tray);
        
                $response_login_tray=json_decode($login_tray,true);
        
                $log= new Log();
                $log->url= 'login_tray';
                $log->request=json_encode($payload_login_tray);
                $log->response=json_encode($response_login_tray);
                $log->save();
    
                $tray_product = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    ])->get('https://www.griftrinetricot.com.br/web_api/products/'.$request->input('scope_id').'?access_token='.$response_login_tray['access_token'],  []);
            
                    $response_tray_product=json_decode($tray_product,true);
    
                    $log= new Log();
                    $log->url= 'get_product_tray: '.$request->input('scope_id');
                    $log->request=json_encode([]);
                    $log->response=json_encode($response_tray_product);
                    $log->save();
                
                    foreach($response_tray_product['Product']['Variant'] as $row){
    
                        SyncProductShopifyCyro::dispatch(['variant_id'=>$row['id'],'access_token'=>$response_login_tray['access_token']])->onQueue('default');
                    }
                    return response()->json($tray, 200);
        }
        
        



        return response()->json([], 200);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function shopify_webhook(Request $request)
    {
        $webhook= json_decode($request->getContent(),true);

        $log=new Log();
        $log->url='webhook_shopify';
        $log->request=json_encode($webhook);
        $log->save();
    }


    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function cartpanda_webhook(Request $request)
    {
        $webhook= json_decode($request->getContent(),true);

        $log=new Log();
        $log->url='webhook_cartpanda';
        $log->request=json_encode($webhook);
        $log->save();


        if ($webhook['event']=="abandoned.created"  ){

            $data=[
                "nome"=>$webhook['data']['customer']['first_name'],
                "phone"=>$webhook['data']['customer']['phone'],
                "link"=>$webhook['data']['cart_url'],
                "event"=>$webhook['event'],
                "webhook"=>$webhook
            ];

            SendMessageCyro::dispatch($data)->onQueue('default');
            
        }

        if ($webhook['event']=="order.updated" || $webhook['event']=="order.paid" ){

            $data=[
                "nome"=>$webhook['order']['customer']['first_name'],
                "phone"=>$webhook['order']['customer']['phone'],
                
                "event"=>$webhook['event'],
                "webhook"=>$webhook
            ];

            SendMessageCyro::dispatch($data)->onQueue('default');
            
        }

 



    }



     /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function products(Request $request)
    {
        $webhook= json_decode($request->getContent(),true);

        $log=new Log();
        $log->url='products';
        $log->request=json_encode($webhook);
        $log->save();

       
            foreach($webhook['variants'] as $row){

                $variant= new CyroSku();
                $variant->product_id=$row['product_id'];
                $variant->variant_id=$row['id'];
                $variant->variant_name=$row['title'];
                $variant->product_name=$webhook['title'];
                $variant->sku=$row['sku'];
                $variant->save();

            }
        
    }

   
}
