@extends('controle-de-gestion.layout')

@section('create')
    <center>
        @foreach ($units as $unit)
            <input type="hidden" name="unit_id[]" value="{{ $unit->id }}">
        @endforeach
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                                    role="alert">
                                    <div class="flex">
                                        <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path
                                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                                            </svg></div>
                                        <div>
                                            <p class="font-bold">Our privacy policy has changed</p>
                                            <p class="text-sm">'Journal crée avec succès.'</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form action="{{ route('controle-de-gestion.store') }}" method="POST">
                                @csrf

                                <select name="unit_id[]"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-max p-3"
                                    hidden>
                                    <option value="{{ $unit->id }}" selected class="hidden">{{ $unit->id }}</option>
                                </select>

                                <div class="flex w-full justify-between p-4 px-10 items-center gap-4">

                                    <h1 class="text-3xl font-bold">{{ $unit->name }}</h1>

                                    <div class="flex flex-col">

                                        <input type="date" id="date" name="date" value="{{ old('date') }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5">
                                        @error('date')
                                            <div class="text-red-500 mt-2 text-sm">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <h1 class="text-3xl font-bold">{{ now()->format('Y-m-d') }}</h1>



                                </div>

                                <center>
                                    <label for="large-input"
                                        class=" flex  font-medium text-gray-900 dark:text-white  text-3xl px-6 py-3 justify-center">Flash
                                        D'Activité Journal </label>
                                    <br>

                                    <div class="flex flex-col">

                                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                                            <thead
                                                class="text-xs text-gray-700 uppercase bg-red-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3">Nom Produit</th>
                                                    <th scope="col" class="px-6 py-3">Previsions Production</th>
                                                    <th scope="col" class="px-6 py-3">Realisation Production</th>
                                                    <th scope="col" class="px-6 py-3">Previsions Vent</th>
                                                    <th scope="col" class="px-6 py-3">Realisation Vent</th>
                                                    <th scope="col" class="px-6 py-3">Previsions Production Vendue</th>
                                                    <th scope="col" class="px-6 py-3">Realisation Production Vendue</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach ($unit->activites as $activite)
                                                    @foreach ($activite->familles as $famille)
                                                        @foreach ($famille->produits as $produit)
                                                            <tr
                                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                                                                <input type="hidden" name="produit_id[]"
                                                                    value="{{ $produit->id }}">
                                                                <td class="px-6 py-4">{{ $produit->name }}</td>
                                                                <td class="px-6 py-4">
                                                                    <input type="number" name="Previsions_Production[]"
                                                                        class="w-full bg-transparent border-none "
                                                                        placeholder="Previsions Production"
                                                                        value="{{ old('Previsions_Production.' . $loop->index) }}">
                                                                    @error('Previsions_Production.*')
                                                                        <span
                                                                            class="text-sm text-red-500">{{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <input type="number" name="Realisation_Production[]"
                                                                        class="w-full bg-transparent border-none @error('Realisation_Production.*') border-red-500 @enderror"
                                                                        placeholder="Realisation Production"
                                                                        value="{{ old('Realisation_Production.' . $loop->index) }}">
                                                                    @error('Realisation_Production.*')
                                                                        <span
                                                                            class="text-sm text-red-500">{{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <input type="number" name="Previsions_Vent[]"
                                                                        class="w-full bg-transparent border-none @error('Previsions_Vent.*') border-red-500 @enderror"
                                                                        placeholder="Previsions Vente "
                                                                        value="{{ old('Previsions_Vent.' . $loop->index) }}">
                                                                    @error('Previsions_Vent.*')
                                                                        <span
                                                                            class="text-sm text-red-500">{{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <input type="number" name="Realisation_Vent[]"
                                                                        class="w-full bg-transparent border-none @error('Realisation_Vent.*') border-red-500 @enderror"
                                                                        placeholder="Realisation Vente"
                                                                        value="{{ old('Realisation_Vent.' . $loop->index) }}">
                                                                    @error('Realisation_Vent.*')
                                                                        <span
                                                                            class="text-sm text-red-500">{{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <input type="number"
                                                                        name="Previsions_ProductionVendue[]"
                                                                        class="w-full bg-transparent border-none @error('Previsions_ProductionVendue.*') border-red-500 @enderror"
                                                                        placeholder="Previsions Production Vendue "
                                                                        value="{{ old('Previsions_ProductionVendue.' . $loop->index) }}">
                                                                    @error('Previsions_ProductionVendue.*')
                                                                        <span
                                                                            class="text-sm text-red-500">{{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <input type="number"
                                                                        name="Realisation_ProductionVendue[]"
                                                                        class="w-full bg-transparent border-none @error('Realisation_ProductionVendue.*') border-red-500 @enderror"
                                                                        placeholder="Realisation Production Vendue "
                                                                        value="{{ old('Realisation_ProductionVendue.' . $loop->index) }}">
                                                                    @error('Realisation_ProductionVendue.*')
                                                                        <span
                                                                            class="text-sm text-red-500">{{ $message }}</span>
                                                                    @enderror
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                </center>
                                <br>

                                <div class="mb-6">
                                    <label for="large-input"
                                        class=" flex  font-medium text-gray-900 dark:text-white  text-3xl px-6 py-3 justify-center">Description
                                    </label>

                                    <br><input type="text" id="large-input"
                                        class="block w-3/4 p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                                @error('description')
                                    <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                                @enderror


                                <div class="flex justify-center items-center gap-6 mt-4">

                                    <button type="submit" onclick="submitForm()" id="submit-all"
                                        class="bg-[#F16B07] rounded-xl w-2/4 h-11 text-lg text-white hover:bg-[#a44a06]"
                                        onclick="return confirm('Are you sure you want to submit the form?')">Submit</button>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </center>
@endsection
