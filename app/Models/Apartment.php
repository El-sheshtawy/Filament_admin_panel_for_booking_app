<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Apartment extends Model
{
    use HasFactory, HasEagerLimit;

    protected $fillable = ['property_id', 'name', 'capacity_adults', 'capacity_children',
        'apartment_type_id', 'size', 'bathrooms'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function apartment_type()
    {
        return $this->belongsTo(ApartmentType::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function beds()
    {
        return $this->hasManyThrough(Bed::class, Room::class);
    }

    public function bedsList(): Attribute
    {
        $allBeds = $this->beds;
        $bedsByType = $allBeds->groupBy('bed_type.name');
        $bedsList = '';

        if ($bedsByType->count() == 1) {
            $bedsList = $allBeds->count() . ' ' . str($bedsByType->keys()[0])->plural($allBeds->count());
        } else if ($bedsByType->count() > 1) {
            $bedsList = $allBeds->count() . ' ' . str('bed')->plural($allBeds->count());
            $bedsListArray = [];
            foreach ($bedsByType as $bedType => $beds) {
                $bedsListArray[] = $beds->count() . ' ' . str($bedType)->plural($beds->count());
            }
            $bedsList .= ' ('.implode(', ' , $bedsListArray) .')';
        }

        return Attribute::make(
            get: fn () => $bedsList
        );
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function prices()
    {
        return $this->hasMany(ApartmentPrice::class);
    }

    public function calculatePriceForDates($startDate, $endDate)
    {
        // Convert to Carbon if not already
        if (!$startDate instanceof Carbon) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }
        if (!$endDate instanceof Carbon) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        $cost = 0;

        while ($startDate->lte($endDate)) {
            $cost += $this->prices->where(function (ApartmentPrice $price) use ($startDate) {
                return $price->start_date->lte($startDate) && $price->end_date->gte($startDate);
            })->value('price');
            $startDate->addDay();
        }

        return $cost;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
