<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\RatingGallery;
use App\Models\Order;
use App\Models\User;
use App\Http\Resources\Rating\RatingResource;
use App\Http\Requests\API\RatingRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Storage;
class RatingController extends Controller
{
 /**
     * @OA\Get(
     *      path="/rating/{product_id}",
     *      operationId="rating",
     *      tags={"Rating"},
     *
  *         @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     * *         @OA\Parameter(
     *         name="keyword",
     *         in="path",
     *         description="search title/description",
     *         required=true,
     *      ),*         @OA\Parameter(
     *         name="type",
     *         in="path",
     *         description="ASC/DESC",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/RatingResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index($id , Request $request)
    {

        $search= $request['keyword']?? null;
        $order= $request['type'] ?? 'DESC';

        $rating = Rating::with('user','product','ratingGallery')
        ->where('product_id',$id)
        ->where(function ($query) use ($search) {
            $query->where('title', "like", "%" . $search . "%");
            $query->orWhere('description', "like", "%" . $search . "%");})
        ->orderBy('id',$order)->get();

        return  RatingResource::collection($rating);

    }

/**
     * @OA\Post(
     *      path="/rating/create",
     *      operationId="Rating Request store",
     *      tags={"Rating"},
     *
     *     summary="RatingRequest store",
     *  *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RatingRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/RatingResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(RatingRequest $request)
    {
        $user = auth('api')->user();

        $inputs['verified_buyer']=0;

        if (!$user)
        {
            $roleGuest = Role::where(['name' => 'Guest'])->first();
            $user = User::updateOrCreate(
                            [
                                'email' => $request->email,
                            ],
                            [
                                'name' => $request->name,
                                'password' => bcrypt(uniqid(rand(), true))
            ]);
            $user->assignRole($roleGuest);
            $inputs['verified_buyer']=0;
        }

        $orderCount = Order::where('user_id',$user->id)->count();

        if($orderCount>0)
        {
            $inputs['verified_buyer']=1;
        }
        $inputs['user_id']=$user->id ;
        $inputs['title']=$request->title;
        $inputs['description']=$request->description;
        $inputs['product_id']=$request->product_id;
        $inputs['rating']=$request->rating;
        $inputs['status']=$request->status ?? 0;

        $rating= Rating::create($inputs);
            if ($request->file('images'))
            {
                foreach ($request->images as  $value) {
                    $path = Storage::disk('s3')->put('images/rating', $value);
                    $path = Storage::disk('s3')->url($path);
                    RatingGallery::create( [
                        'rating_id' => $rating->id,
                        'image_path' => $path
                    ]);
                }

            }

            return response()->json([
                'success' => true,'message' => 'Rating created successfull'
            ]);
    }

     /**
     * @OA\Get(
     *      path="/rating/overall/{product_id}",
     *      operationId="overall rating",
     *      tags={"Rating"},
     *
     *     summary="over all rating",
     *     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/OverAllRatingResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function getOverallRating($id)
    {
     $singleRating=   Rating::where('product_id',$id)->first();
     $rating=   Rating::where('product_id',$id)->get();
        if(isset($singleRating)){
            foreach ($rating as $key => $value) {

                $overall[]=$value->rating;

            }
            $totalSum=array_sum($overall);
            $totalCount=count($overall);
                $overAllRating=$totalSum/$totalCount;
                return response()->json([
                    'success' => true,'overAllRating' => $overAllRating,'total_reviews' =>$totalCount
                ]);
        }
            return response()->json([
                'success' => false,'message' => 'No record found'
            ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



}
