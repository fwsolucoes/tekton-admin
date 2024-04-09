<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * 
 * @property int $id
 * @property string|null $url
 * @property string|null $request
 * @property string|null $response
 * @property string|null $code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class TiradentesEvents extends Model
{
	
	protected $connection = 'mysql_tiradentes';
	protected $table = 'events';
	
}
