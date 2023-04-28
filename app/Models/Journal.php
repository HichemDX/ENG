<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;

class Journal extends Model
{
  use HasFactory;

  protected $table = 'journals';

  public $timestamps = false;

  protected $fillable = [
    'date',
    'Previsions_Production',
    'Previsions_Vent',
    'Previsions_ProductionVendue',
    'Realisation_Production',
    'Realisation_Vent',
    'Realisation_ProductionVendue',
    'description',
    'unit_id',
    'produit_id',
  ];



  public function units()
{
    return $this->belongsToMany(Unit::class,  'journal_unit', 'journal_id', 'unit_id'  );
}



  //write a function to show produits name from journals
  public function produits()
  {
    return $this->belongsTo(Produit::class);
  }
}
