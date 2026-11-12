<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Get the items that belong to the category.
     */
    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
