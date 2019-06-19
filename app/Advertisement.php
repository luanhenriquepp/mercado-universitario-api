<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;


/**
 * @property string cd_category
 * @property array|null|string price
 * @property array|null|string ds_advertisement
 * @property array|null|string title
 * @property  int mixed
 * @property array|null|string cd_advertisement_status
 * @property array|null|string advertisement_photo
 */
class Advertisement extends Model
{
    /**
     * @var string
     */
    protected $table = 'tb_advertisement';
    
    /**
     * @var string
     */
    protected $primaryKey = 'cd_advertisement';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'ds_advertisement',
        'price',
        'advertisement_photo',
        'cd_user',
        'cd_category',
        'cd_advertisement_status',
    ];

    protected $appends = [
        'file_path'
    ];
    
    /**
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'cd_user');
    }

    /**
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class,'cd_category');
    }

    /**
     * @return BelongsTo
     */
    public function advertisement_status()
    {
        return $this->belongsTo(AdvertisementStatus::class,'cd_advertisement_status');
    }

    public function getFilePathAttribute()
    {
        return Storage::url($this->attributes['advertisement_photo']);
    }
}
