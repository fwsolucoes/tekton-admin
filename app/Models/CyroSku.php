<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CyroSku
 * 
 * @property int $id
 * @property int $product_id
 * @property string|null $variant_id
 * @property string|null $sku
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class CyroSku extends Model
{
	protected $table = 'cyro_skus';

	protected $casts = [
		'product_id' => 'int'
	];

	protected $fillable = [
		'product_id',
		'variant_name',
		'product_name',
		'variant_id',
		'sku'
	];
}
