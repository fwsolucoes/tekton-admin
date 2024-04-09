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
use App\Models\TiradentesEvents;

use App\Models\CyroSku;
use App\Jobs\SyncProductShopifyCyro;
use App\Jobs\SendMessageCyro;

use Illuminate\Support\Facades\Http;

class TiradentesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function teste(Request $request)
    {
        
    
           
    
            $teste=TiradentesEvents::find(65);

            $log=new Log();
            $log->url='TiradentesEvents';
            $log->request=json_encode($teste);
            $log->save();
        
        



        return response()->json($teste, 200);
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
