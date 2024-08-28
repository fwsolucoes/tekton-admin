<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CyroOrder
 * 
 * @property int $id
 * @property int $cartpanda_order_id
 * @property Carbon $new_order_sent
 * @property Carbon $new_order_processed
 *
 * @package App\Models
 */
class UnitrinusList extends Model
{
	protected $table = 'lists';
	protected $connection = 'unitrinus';
	
}
