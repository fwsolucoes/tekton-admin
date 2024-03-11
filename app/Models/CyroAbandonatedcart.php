<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CyroAbandonatedcart
 * 
 * @property int $id
 * @property string|null $payload
 * @property string|null $message
 * @property string|null $phone
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $message_sent_at
 *
 * @package App\Models
 */
class CyroAbandonatedcart extends Model
{
	protected $table = 'cyro_abandonatedcarts';

	protected $casts = [
		'message_sent_at' => 'datetime'
	];

	protected $fillable = [
		'payload',
		'message',
		'phone',
		'message_sent_at'
	];
}
