<?php

namespace App\Http\Resources\API\Profile;

// Http

use App\Http\Resources\API\Categories\CategoriesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// Resources
use App\Http\Resources\API\Time\TimeResource;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Notification;
// Carbon
use Carbon\Carbon;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'              => $this->name,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'birthday'          => Carbon::parse($this->birthday)->format('Y/m/d'),
            'gender'            => $this->gender,
            'disabilities'      => ($this->disabilities == 1) ? true : false,
            'time'              => new TimeResource($this->time),
            'avatar'            => $this->display_image,
            'account_by'        => $this->account_by,
            "has_notifications" => $this->hasNotifications(),
            // "categories"        => CategoriesResource::collection($this->categories()->get()),
            "categories"        => $this->getCategories(),
        ];
    }

    private function getCategories() {
        if($this->categories()->count() == 0) {
            return [];
        }
        // ====== //
        $GlobalChildren     = $this->categories()->where("parent_id","!=",0)->pluck("category_id")->toArray();
        // ====== //
        $array  = [];
        $x      = 0;
        foreach($this->categories()->where("parent_id",0)->get() as $cat) {
            $array[$x] = [
                "id"                => $cat->id,
                "name"              => $cat->name,
                "background_color"  => $cat->hexacode_color ?? '#FFFFFF',
                "image"             => $cat->display_image,
                "children"          => []
            ];
            $categories = Category::where("parent_id",$cat->id)->whereIn("id",$GlobalChildren)->get();
            $y          = 0;
            foreach ($categories as $item) {
                $array[$x]["children"][$y] = [
                    "id"                => $item->id,
                    "name"              => $item->name,
                    "background_color"  => $item->hexacode_color ?? '#FFFFFF',
                    "image"             => $item->display_image,
                ];
                $y = $y + 1;
            }
            $x = $x + 1;
        }
        return $array;
    }

    private function hasNotifications() {
        $c = Notification::where("user_id",$this->id)->where("seen",0)->count();
        return ($c > 0) ? true : false;
    }

    private function hasMessage() {
        $id = $this->id;
        $c = Chat::where("customer_id",$this->id)->whereHas("messages",function($q)use($id){
            return $q->where("user_id","!=",$id)->where("seen",0);
        })->count();
        return ($c > 0) ? true : false;
    }
}
