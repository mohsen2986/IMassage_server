<?php

namespace App;

use App\Transformers\AboutUsTransformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AboutUs
 * @package App
 * @property mixed description
 * @property mixed image
 */
class AboutUs extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'description' ,
        'image' ,
    ];
}
