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
class CyroOrder extends Model
{
	protected $table = 'cyro_orders';
	public $timestamps = false;

	protected $casts = [
		'cartpanda_order_id' => 'int',
		'new_order_sent' => 'datetime',
		'new_order_processed' => 'datetime'
	];

	protected $fillable = [
		'cartpanda_order_id',
		'new_order_sent',
		'new_order_processed'
	];
}
