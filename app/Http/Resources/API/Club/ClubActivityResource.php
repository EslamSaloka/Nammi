<?php

namespace App\Http\Resources\API\Club;

// Http

use App\Http\Resources\API\Categories\CategoriesResource;
use App\Http\Resources\API\City\CityResource;
use App\Http\Resources\API\Country\CountryResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => (int)$this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'image'         => $this->display_image,
            'price'         => (float)$this->price,
            'is_offer'      => ($this->offer == 0) ? false : true,
            'offer_date'    => [
                'price'             => (float)$this->offer,
                'percentage'        => $this->offer_percentage,
                'start_offer'       => Carbon::parse($this->start_offer)->format("d/m/y"),
                'end_offer'         => Carbon::parse($this->end_offer)->format("d/m/y"),
                'customer_count'    => (int)$this->customer_count,
            ],
            "rates"             => $this->rates,
            "order_one_time"    => ($this->order_one_time == 0) ? false : true,
            'disabilities'      => ($this->disabilities == 0) ? false : true,
            'payment_types'     => $this->payment_types,
            'created_at'        => Carbon::parse($this->created_at)->format('d/m/Y'),
            "branch"            => ClubBranchResource::collection([$this->branch]),
            //"branch"            =>  ClubBranchResource::collection($this->branch()->get()), //get as a list

            // 'country'           => new CountryResource($this->country),
            // 'city'              => new CityResource($this->city),
            'categories'        => [
                "mainCategory"    => CategoriesResource::collection($this->categories()->where("parent_id",0)->get()),
                "subCategory"     => CategoriesResource::collection($this->categories()->where("parent_id","!=",0)->get()),
            ],
        ];
    }
}
