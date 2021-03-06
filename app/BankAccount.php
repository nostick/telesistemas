<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bank_accounts';

    public static $rules = array(
        'titular' => 'required|max:100|min:3',
        'nro_cuenta' => 'required|digits:20',
        'tipo' => 'required|max:2',
        'rif_ci' => 'required|max:15|min:8',
        'bank_id' => 'required',
        'enterprise_id' => 'required'
    );

    protected $fillable = array('titular', 'nro_cuenta', 
                                'tipo', 'rif_ci', 'bank_id', 'enterprise_id');

    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }

    public function enterprise()
    {
        return $this->belongsTo('App\Enterprise');
    }
}
