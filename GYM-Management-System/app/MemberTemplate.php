<?php

namespace App;

use Carbon\Carbon;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class MemberTemplate extends Model
{
    //Eloquence Search mapping
    use Eloquence;
    use createdByUser, updatedByUser;

    protected $table = 'trn_member_template';

    protected $fillable = [
        'member_id',
        'uid',
        'fid',
        'size',
        'valid',
        'bio_temp',
        'created_by',
        'updated_by',
    ];

}
