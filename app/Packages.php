<?php

namespace App;

use App\Transformers\PackageTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Packages
 * @package App
 * @property mixed name
 * @property mixed description
 * @property mixed image
 * @property mixed cost
 * @property mixed massage_id
 * @property mixed length
 *
 */
class Packages extends Model
{

    /**
     * @var array
     *
     */
    protected $fillable = [
        'name' ,
        'description' ,
        'image' ,
        'cost' ,
        'massage_id' ,
        'length' ,
    ];
    // RELATIONS
    public function massage(){
        return $this->belongsTo(Massage::class);
    }
    public function Order(){
        return $this->hasMany(Order::class);
    }
    public function timeConfigs(){
        return $this->belongsToMany(TimeConfig::class);
    }
}
