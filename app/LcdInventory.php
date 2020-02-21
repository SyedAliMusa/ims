<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LcdInventory extends Model
{

    protected $fillable = ["*"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function issued()
    {
        return $this->hasOne(LcdIssuedTo::class,'lcd_inventory_id');
    }


}
