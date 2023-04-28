<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Journal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class JournalsController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $journals = Journal::query();

    // Filter by date
    if ($request->has('date')) {
      $journals->whereDate('date', $request->date);
    }

    // Filter by product ID
    if ($request->has('produit_id')) {
      $journals->where('produit_id', $request->produit_id);
    }

    // Filter by unit ID
    if ($request->has('unit_id')) {
      $journals->where('unit_id', $request->unit_id);
    }

    $journals = $journals->get();
    $products = $journals->groupBy('produit_id');
    $units = $journals->groupBy('unit_id');
    $dates = $journals->groupBy('date');

    $totals = [];
    foreach ($products as $productId => $productJournals) {

      foreach ($dates as $date => $dateJournals) {
        $totals[$productId][$date] = [
          'Realisation_Production' => $productJournals->where('date', $date)->sum('Realisation_Production'),
          'Realisation_Vent' => $productJournals->where('date', $date)->sum('Realisation_Vent'),
          'Realisation_ProductionVendue' => $productJournals->where('date', $date)->sum('Realisation_ProductionVendue'),

          'Previsions_Production' => $productJournals->where('date', $date)->sum('Realisation_Production'),
          'Previsions_Vent' => $productJournals->where('date', $date)->sum('Realisation_Vent'),
          'Previsions_ProductionVendue' => $productJournals->where('date', $date)->sum('Realisation_ProductionVendue'),
        ];
      }
    }

    return view('admin.index', compact('journals', 'totals'));
  }



  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {


    // Récupérer l'utilisateur connecté
    $user = Auth::user();

    // Récupérer les unités de l'utilisateur connecté
    $units = $user->units;

    // Récupérer les activités pour chaque unité de l'utilisateur connecté
    $activites = collect();
    foreach ($units as $unit) {
      $activites = $activites->merge($unit->activites);
    }

    // Récupérer les familles pour chaque activité
    $familles = collect();
    foreach ($activites as $activite) {
      $familles = $familles->merge($activite->familles);
    }

    // Récupérer les produits pour chaque famille
    $produits = collect();
    foreach ($familles as $famille) {
      $produits = $produits->merge($famille->produits);
    }

    // Passer l'utilisateur connecté, ses unités, les activités, les familles et les produits associés à la vue index
    return view('controle-de-gestion.create', compact('user', 'units', 'activites', 'familles', 'produits'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // validate the form data
    $validatedData = $request->validate([
      'unit_id.*' => 'required|integer',
      'produit_id.*' => 'required|integer',
      'Previsions_Production.*' => 'required|numeric',
      'Previsions_Vent.*' => 'required|numeric',
      'Previsions_ProductionVendue.*' => 'required|numeric',
      'Realisation_Production.*' => 'required|numeric',
      'Realisation_Vent.*' => 'required|numeric',
      'Realisation_ProductionVendue.*' => 'required|numeric',
      'description' => 'required',
      'date' => 'required|date',

    ]);

    // dd($request->all());
    //   Récupérer l'utilisateur connecté
    $user = Auth::user();

    // Récupérer les unités de l'utilisateur connecté
    $units = $user->units;

    // Récupérer les activités pour chaque unité de l'utilisateur connecté
    $activites = collect();
    foreach ($units as $unit) {
      $activites = $activites->merge($unit->activites);
    }

    // Récupérer les familles pour chaque activité
    $familles = collect();
    foreach ($activites as $activite) {
      $familles = $familles->merge($activite->familles);
    }

    // Récupérer les produits pour chaque famille
    $produits = collect();
    foreach ($familles as $famille) {
      $produits = $produits->merge($famille->produits);
    }
    $produits = collect();
    foreach ($familles as $famille) {
      $produits = $produits->merge($famille->produits()->with('mesure')->get());
    }

    //     loop through the form data and create Journal models
    for ($i = 0; $i < $produits->count(); $i++) {
      try {
        $journal = new Journal();
        $journal->unit_id = $validatedData['unit_id'][0];
        $journal->produit_id = $validatedData['produit_id'][$i];
        $journal->Previsions_Production = $validatedData['Previsions_Production'][$i];
        $journal->Previsions_Vent = $validatedData['Previsions_Vent'][$i];
        $journal->Previsions_ProductionVendue = $validatedData['Previsions_ProductionVendue'][$i];
        $journal->Realisation_Production = $validatedData['Realisation_Production'][$i];
        $journal->Realisation_Vent = $validatedData['Realisation_Vent'][$i];
        $journal->Realisation_ProductionVendue = $validatedData['Realisation_ProductionVendue'][$i];
        $journal->date = $validatedData['date'];
        $journal->description = $validatedData['description'];
        $journal->save();
        $journal->units()->attach($validatedData['unit_id'][0]);
      } catch (\Illuminate\Database\QueryException $ex) {
        $errorCode = $ex->errorInfo[1];
        if ($errorCode == 1062) { // duplicate entry error
          // display custom error message

          return redirect()->back()->withErrors('A journal with the same unit, date and product already exists.')->withInput($request->all());
        }
        // rethrow the exception if it's not a duplicate entry error
      }
    }

    // redirect to a success page


    // Redirect back to the create page
    return redirect()->back();
  }



  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Journal $journal)
  {
    // Récupérer l'utilisateur connecté
    $user = Auth::user();

    // Récupérer les unités de l'utilisateur connecté
    $units = $user->units;

    // Récupérer les activités pour chaque unité de l'utilisateur connecté
    $activites = collect();
    foreach ($units as $unit) {
      $activites = $activites->merge($unit->activites);
    }

    // Récupérer les familles pour chaque activité
    $familles = collect();
    foreach ($activites as $activite) {
      $familles = $familles->merge($activite->familles);
    }

    // Récupérer les produits pour chaque famille
    $produits = collect();
    foreach ($familles as $famille) {
      $produits = $produits->merge($famille->produits()->with('mesure')->get());
    }

    // Passer le journal à la vue edit ainsi que les autres informations récupérées
    return view('admin.edit', compact('journal', 'user', 'units', 'activites', 'familles', 'produits'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Journal $journal)
  {
    // validate the form data
    $validatedData = $request->validate([
      'unit_id' => 'required|integer',
      'produit_id' => 'required|integer',
      'Previsions_Production' => 'required|numeric',
      'Previsions_Vent' => 'required|numeric',
      'Previsions_ProductionVendue' => 'required|numeric',
      'Realisation_Production' => 'required|numeric',
      'Realisation_Vent' => 'required|numeric',
      'Realisation_ProductionVendue' => 'required|numeric',
      'description' => 'required',
      'date' => 'required|date',
    ]);
  
    // update the journal with the new data
    $journal->update($validatedData);
  
    // redirect to the journals index page with a success message
    return redirect()->route('admin.index')->with('success', 'Journal entry updated successfully');
  }
  


  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
