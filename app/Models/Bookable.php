<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class Bookable extends Model
{
    use HasApiTokens, HasFactory;

    // protected $fillable = [
    //     'title',
    //     'description',
    // ];

    protected $guarded = ['id'];

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function availableFor($from, $to):bool{
        return 0 === $this->bookings()->betweenDates($from, $to)->count();
    }

    public function pricefor($from, $to):array
    {
        $days = (new Carbon($from))->diffInDays(new Carbon($to)) + 1;
        $price = $days * $this->price;

        return [
                'total' => $price,
                'breakdown' => [
                    $this->price => $days
                ]
        ];
    }
}
