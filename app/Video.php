<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    //Relation one to many
    public function comments(){
      return $this->hasMany('App\Comment')->orderBy('id', 'desc');
      //hasOne fot one to one
    }

    //Relation many to one
    public function user(){
      return $this->belongsTo('App\User','user_id');
    }
}
