<?php

namespace App\Http\Resources;

use App\Models\RecipeCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $category = $this->whenLoaded('recipe');
        $this->recipecategories->userId = $request->userId;
        return [
            'id' => $this->id,  
            'name' => $this->name,      
            'image' => $this->image,
            'status' => $this->status,
            'created_at' => $this->created_at,  
            'updated_at' => $this->updated_at,   
            'recipes' =>  RecipeCategoryResource::collection($this->recipecategories),
        ];
    }
}
