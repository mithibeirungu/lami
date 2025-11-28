<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    // LIST ALL CARS
    public function index()
    {
        return Car::with('admin')->latest()->get();
    }

    // CREATE A CAR (Admin)
    public function store(Request $request)
    {
        $request->validate([
            'car_name' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'year' => 'required|integer',
        ]);

        $user = $request->user();
        if (!$user || $user->type_of_user !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admins only.'], 403);
        }

        $car = Car::create([
            'admin_id' => $user->id,
            'user_id' => $user->id,
            'car_name' => $request->car_name,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'body_type' => $request->input('body_type', ''),
            'fuel_type' => $request->input('fuel_type', ''),
            'engine_power' => $request->input('engine_power', 0),
            'transmission' => $request->input('transmission', ''),
            'top_speed' => $request->input('top_speed', 0),
            'acceleration' => $request->input('acceleration', 0.0),
            'description' => $request->input('description', ''),
            'image_url' => $request->input('image_url', ''),
            'price' => $request->input('price', 0.0),
        ]);

        return response()->json($car);
    }

    // SHOW A SINGLE CAR
    public function show(Car $car)
    {
        return $car->load(['comments.user', 'favorites']);
    }

    // UPDATE A CAR
    public function update(Request $request, Car $car)
    {
        $user = $request->user();
        if (!$user || $user->type_of_user !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admins only.'], 403);
        }

        $car->update($request->only([
            'car_name','brand','model','year','body_type','fuel_type','engine_power','transmission','top_speed','acceleration','description','image_url','price'
        ]));

        return response()->json(['message' => 'Car updated', 'car' => $car]);
    }

    // DELETE A CAR
    public function destroy(Car $car)
    {
        $user = request()->user();
        if (!$user || $user->type_of_user !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admins only.'], 403);
        }

        $car->delete();
        return response()->json(['message' => 'Car deleted']);
    }
}
