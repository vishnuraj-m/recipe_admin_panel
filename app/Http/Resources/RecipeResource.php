<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $category = $this->whenLoaded('recipe_categories');
        return [
            'id' => $this->id,
            'userId' => $this->userId,      
            'name' => $this->name,      
            'image' => $this->image,
            'duration' => $this->duration,
            'noOfServing' => $this->noOfServing,
            'difficulty' => $this->difficulty,
            'cuisine_id' => $this->cuisine_id,
            'ingredients' => $this->ingredients,
            'steps' => $this->steps,
            'websiteUrl' => $this->websiteUrl,  
            'youtubeUrl' => $this->youtubeUrl,  
            'noOfViews' => $this->noOfViews,  
            'noOfLikes' => $this->noOfLikes,  
            'status' => $this->status,
            'created_at' => $this->created_at,  
            'updated_at' => $this->updated_at,      
            'categories' => RecipeCategoryResource::collection($category),
        ];
    }
}
