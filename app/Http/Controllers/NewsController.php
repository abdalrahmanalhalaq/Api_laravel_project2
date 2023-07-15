<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Http\Resources\NewsResourceCollection;
use App\Models\News;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class NewsController extends Controller
{
    public function trasheddata()
    {
        $datatrashed = News::onlyTrashed()->get();
        return new Response(['status'=>true , 'data'=>$datatrashed ,'Message'=>'data trushed']);
    }

    // public function __construct()
    // {
    //     // لازم عشان اط بق هاي الطريقة اكون شغال controller resource (model binding)
        //     $this->authorizeResource(News::class   // => Model Name
        //     , 'News');                              // => attribute name in api route
    //                                             // => كيف ربط مع البوليسي -> ج \ من خلال التسميات اسم البوليسي نفس اسم الموديل بالأضافة لبوليسي
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $data = News::all();
            // $data => عبارة عن اوبجكت في كل بيانات الجدول
            // $i    => عبارة عن حقل من حقول الجدول

        // foreach ($data as $i) {
        //     $all = $i->title . $i->description;
        //     $i->Newcomen = $all;
        // }

        // $data = News::onlyTrashed()->get();
        // return new Response(['status'=>true,'data'=>$data] , Response::HTTP_OK);
        // return NewsResource::collection($data);

            //الطريقة الاولى

        $data = NewsResource::collection(News::all());
        return new Response(['status'=>true,'data'=>$data] , Response::HTTP_OK);

            //الطريقة الثانية

        // $data = News::all();
        // return new NewsResourceCollection($data);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator($request->all(),
        [
            'title'=>'required|string|min:6',
            'description'=>'required|string',
            'img' => 'nullable|image|mimes:jpg,png|max:1024',
        ]);

        if(! $validator->fails()){
                $news = new News();
                $news->title = $request->input('title');
                $news->description = $request->input('description');
                    if($request->hasFile('img'))
                    {
                        $newsImage = $request->file('img');
                        $imageName = time() . '_image_' . $news->name . '.' . $newsImage->getClientOriginalExtension();
                        $newsImage->storePubliclyAs('news' , $imageName , ['disk' =>'public']);
                        $news->img = 'news/' . $imageName;
                    }
                $saved = $news->save();
                $object = new NewsResource($news);
                return new Response(['message'=>$saved ,"object"=>$object ]);

        }else{
            return new Response(['message'=>$validator->getMessageBag()->first()]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {

        // $this->authorize('view', $news);  //  بحط متغير لما بدي افحص امكانية هاد المستخدم قادر انه يتعامل مع هاد العنصر -> بإختصار لما يكون ممر متغير في الدالة
        //
        // $news = News::Paginate();
        return new Response(['status'=>true , 'message'=>'success' , 'data'=>$news]);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news) //الحمد لله
    {
        //
        {
            //
            $validator = Validator($request->all(),
            [
                'title'=>'required|string|min:6',
                'description'=>'required|string',
                'img' => 'nullable|image|mimes:jpg,png|max:1024',
            ]);

            if(! $validator->fails()){

                    $news->title = $request->input('title');
                    $news->description = $request->input('description');
                        if($request->hasFile('img'))
                        {
                            $newsImage = $request->file('img');
                            $imageName = time() . '_image_' . $news->name . '.' . $newsImage->getClientOriginalExtension();
                            $newsImage->storePubliclyAs('news' , $imageName , ['disk' =>'public']);
                            $news->img = 'news/' . $imageName;
                        }
                    $saved = $news->save();
                    return new Response(['message'=>$saved , 'data'=>$news]);

            }else{
                return new Response(['message'=>$validator->getMessageBag()->first()]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
         $deleted = $news->delete();//لحاله بيعمل فايند اور فيل وبيجييب الاي دي
        return response(['status'=>true , 'message'=>$deleted ? 'deleted Successfuly' : 'Updated delete'] , $deleted ? Response::HTTP_OK  : Response::HTTP_BAD_REQUEST);
    }

      /**
     * Determine whether the user can restore the model.
     */
    public function restore(Request $request , $id) //هاذا الديفولت تبعه فايند اور فيل وهو ما بيجيب العناصر التراشد فلازم اقله يجيب العناصر التراشد
    {
        // بعمل الطريقة الثانية الخاصة بالبو ليسي مش الاولى لانه فشي لل ري ستور ابيلتي

        $news = News::withTrashed()->findOrFail($id);
        $restored = $news->restore();
        return new Response(['status'=>$restored]);

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Request $request ,$id)
    {
        //
        $news = News::withTrashed()->findOrFail($id);
         if($news->trashed()){
            $deleted = $news->forceDelete();
            return new Response(['status'=>$deleted , 'Message'=> 'Force Deleted']);

         }else{
            $deleted = $news->delete();
            return new Response(['status'=>$deleted ,'Message'=> 'Soft Deleted']);
        }

    }
}
