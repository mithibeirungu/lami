<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;

class CarController extends Controller
{
    // LIST ALL CARS (with relationships)
    public function index()
    {
        return Car::with(['brand', 'bodyType', 'creator', 'images', 'comments', 'favorites'])
            ->latest('created_at')
            ->get();
    }

    // CREATE A CAR (Admin only)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'brand_id' => 'required|exists:brands,brand_id',
            'model' => 'required',
            'year' => 'required|integer|min:1900|max:2100',
            'body_type_id' => 'required|exists:body_types,body_type_id',
            'transmission' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'engine_type' => 'nullable|string',
            'engine_size' => 'nullable|string',
            'horsepower' => 'nullable|integer|min:0',
            'torque' => 'nullable|integer|min:0',
            'seating_capacity' => 'nullable|integer|min:1',
            'drive_type' => 'nullable|string',
            'description' => 'nullable|string',
            'thumbnail_image' => 'nullable|url',
            'price' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
        ]);

        $user = $request->user();

        $car = Car::create([
            'title' => $request->title,
            'brand_id' => $request->brand_id,
            'model' => $request->model,
            'year' => $request->year,
            'body_type_id' => $request->body_type_id,
            'transmission' => $request->transmission,
            'fuel_type' => $request->fuel_type,
            'engine_type' => $request->engine_type,
            'engine_size' => $request->engine_size,
            'horsepower' => $request->horsepower,
            'torque' => $request->torque,
            'seating_capacity' => $request->seating_capacity,
            'drive_type' => $request->drive_type,
            'description' => $request->description,
            'thumbnail_image' => $request->thumbnail_image,
            'price' => $request->price,
            'created_by' => $user->user_id,
        ]);

        // Add primary image if provided
        if ($request->thumbnail_image) {
            CarImage::create([
                'car_id' => $car->car_id,
                'image_url' => $request->thumbnail_image,
                'title' => 'Main Image',
                'is_primary' => true,
            ]);
        }

        // Add additional images if provided
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $idx => $imageUrl) {
                if ($imageUrl) {
                    CarImage::create([
                        'car_id' => $car->car_id,
                        'image_url' => $imageUrl,
                        'title' => "Image " . ($idx + 1),
                        'is_primary' => false,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Car created',
            'car' => $car->load(['brand', 'bodyType', 'creator', 'images']),
        ], 201);
    }

    // SHOW A SINGLE CAR
    public function show(Car $car)
    {
        return $car->load([
            'brand',
            'bodyType',
            'creator',
            'images',
            'comments.user',
            'favorites'
        ]);
    }

    // UPDATE A CAR (Admin only)
    public function update(Request $request, Car $car)
    {
        $request->validate([
            'title' => 'sometimes|required',
            'brand_id' => 'sometimes|required|exists:brands,brand_id',
            'model' => 'sometimes|required',
            'year' => 'sometimes|required|integer|min:1900|max:2100',
            'body_type_id' => 'sometimes|required|exists:body_types,body_type_id',
            'transmission' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'engine_type' => 'nullable|string',
            'engine_size' => 'nullable|string',
            'horsepower' => 'nullable|integer|min:0',
            'torque' => 'nullable|integer|min:0',
            'seating_capacity' => 'nullable|integer|min:1',
            'drive_type' => 'nullable|string',
            'description' => 'nullable|string',
            'thumbnail_image' => 'nullable|url',
            'price' => 'nullable|numeric|min:0',
        ]);

        $car->update($request->only([
            'title', 'brand_id', 'model', 'year', 'body_type_id', 'transmission',
            'fuel_type', 'engine_type', 'engine_size', 'horsepower', 'torque',
            'seating_capacity', 'drive_type', 'description', 'thumbnail_image', 'price'
        ]));

        // Update primary image if provided
        if ($request->thumbnail_image) {
            $car->images()->where('is_primary', true)->delete();
            CarImage::create([
                'car_id' => $car->car_id,
                'image_url' => $request->thumbnail_image,
                'title' => 'Main Image',
                'is_primary' => true,
            ]);
        }

        return response()->json([
            'message' => 'Car updated',
            'car' => $car->load(['brand', 'bodyType', 'creator', 'images']),
        ]);
    }

    // DELETE A CAR (Admin only)
    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json(['message' => 'Car deleted']);
    }

    // GET ALL BRANDS (for dropdowns)
    public function getBrands()
    {
        return response()->json(\App\Models\Brand::select('brand_id', 'name')->get());
    }

    // GET ALL BODY TYPES (for dropdowns)
    public function getBodyTypes()
    {
        return response()->json(\App\Models\BodyType::select('body_type_id', 'name')->get());
    }
}
