<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CyroMessage
 * 
 * @property int $id
 * @property string $name
 * @property string $message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class CyroMessage extends Model
{
	protected $table = 'cyro_messages';

	protected $fillable = [
		'name',
		'message'
	];
}
