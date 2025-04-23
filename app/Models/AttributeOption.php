<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeOption extends Model
{
  protected $fillable = [
      'attribute_id',
      'name',
      'keyword',
      'price',
      'stock',

      // newly added:
      'sale_price',
  ];

  protected $casts = [
      'price'                  => 'decimal:2',
      'sale_price'             => 'decimal:2',
  ];

  public function attribute() {
    return $this->belongsTo('App\Models\Attribute')->withDefault();
  }

  public $timestamps = false;

}
