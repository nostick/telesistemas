<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    protected $table = 'representatives';

    public static $rules_rl = array(
        'nombre_rl' => 'required|max:100|min:3',
        'apellido_rl' => 'required|max:100|min:3',
        'ci_rl' => 'required|max:15|min:8',
        'rif_rl' => 'required|max:20|min:9',
        'email_rl' => 'required|email',
        'telefono_rl' => 'required|alpha_dash',
        'direccion_rl' => 'required|max:200|min:3',
    );

    public static $rules_ct = array(
        'nombre_ct' => 'required|max:100|min:3',
        'apellido_ct' => 'required|max:100|min:3',
        'ci_ct' => 'required|max:15|min:8',
        'rif_ct' => 'required|max:20|min:9',
        'email_ct' => 'required|email',
        'telefono_ct' => 'required|alpha_dash',
        'direccion_ct' => 'required|max:200|min:3',
    );

    public static $rules = array(
        'nombre' => 'required|max:100|min:3',
        'apellido' => 'required|max:100|min:3',
        'ci' => 'required|max:15|min:8',
        'rif' => 'required|max:20|min:9',
        'email' => 'required|email',
        'telefono' => 'required|alpha_dash',
        'direccion' => 'required|max:200|min:3',
    );

    protected $fillable = array('nombre', 'apellido', 
                                'ci', 'rif',
                                'email', 'telefono', 
                                'direccion');

    public function enterprises()
    {
        return $this->belongsToMany('App\Enterprise', 'enterprises_representatives');
    }
}
