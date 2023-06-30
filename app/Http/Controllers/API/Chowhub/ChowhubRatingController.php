<?php

namespace App\Http\Controllers\API\Chowhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\ChowhubRating;
use App\Models\User;
use App\Http\Resources\Rating\ChowhubRatingResource;
use App\Http\Requests\API\RatingRequest;
use Storage;
class ChowhubRatingController extends Controller
{
 /**
     * @OA\Get(
     *      path="/chowhub/rating/{product_id}",
     *      operationId="chowhub rating",
     *      tags={"Rating"},
     *
*         @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
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

    public function index($id)
    {
        $rating = ChowhubRating::with('user','product')->where('product_id',$id)->orderBy('id', 'DESC')->get();

        return  ChowhubRatingResource::collection($rating);

    }

/**
     * @OA\Post(
     *      path="/chowhub/rating/create",
     *      operationId="chowhub Rating Request store",
     *      tags={"Rating"},
     *
     *     summary="chowhub Rating Request store",
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
        }

        $inputs['user_id']=$user->id ;
        $inputs['description']=$request->description;
        $inputs['product_id']=$request->product_id;
        $inputs['rating']=$request->rating;
        $inputs['status']=$request->status ?? 0;
        $rating= ChowhubRating::create($inputs);
        if ($request->file('images'))
        {
            foreach ($request->images as  $value) {
                $path = Storage::disk('s3')->put('images/rating', $value);
                $path = Storage::disk('s3')->url($path);
                ChowhubRatingGallery::create( [
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
     *      path="/chowhub/rating/overall/{product_id}",
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
     $singleRating=   ChowhubRatingRating::where('product_id',$id)->first();
     $rating=   ChowhubRatingRating::where('product_id',$id)->get();
        if(isset($singleRating)){
            foreach ($rating as $key => $value) {

                $overall[]=$value->rating;

            }
            $totalSum=array_sum($overall);
            $totalCount=count($overall);
                $overAllRating=$totalSum/$totalCount;
                return response()->json([
                    'success' => true,'overAllRating' => $overAllRating,'total-reviews' =>$totalCount
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
