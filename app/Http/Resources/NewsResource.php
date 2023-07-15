<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = '';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
        'id' => $this->id,
        'status'=>true,
        'message'=>'successfully',
        'title'=>$this->title,
        'description'=>$this->description,
        'if statement' =>$this->when(strlen($this->title) > 3 , 'bigger than 3'), //شرط للحقولب

        'title count'=>strlen($this->title),

        ];
    }
}
