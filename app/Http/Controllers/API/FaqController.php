<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\ChowhubFaq;

use App\Models\User;

use App\Http\Resources\Faqs\FaqResource;
use App\Http\Resources\Faqs\ChowhubFaqResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\API\FaqRequest;
class FaqController extends Controller
{
      /**
     * @OA\Get(
     *      path="/faq/{product_id}",
     *      operationId="faqs",
     *      tags={"Faqs"},
     *
     *     summary="Faqs",
     *     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *       @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="search title/description",
     *         required=false,
     *      ),

     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/FaqResponse")
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

    public function index($id,Request $request)
    {
        $search= $request['keyword']?? null;
        $faqs = Faq::with('user','product')
                ->where(['product_id'=>$id,'published'=>1])
                ->where(function ($query) use ($search) {
                $query->where('title', "like", "%" . $search . "%");
                $query->orWhere('description', "like", "%" . $search . "%");})
                ->orderBy('id', 'DESC')
                ->get();

        return  FaqResource::collection($faqs);

    }

  /**
     * @OA\Post(
     *      path="/faq/store",
     *      operationId="faqs store",
     *      tags={"Faqs"},
     *
     *     summary="Faqs store",
     *  *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FaqRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/FaqResponse")
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
    public function store(FaqRequest $request)
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


                $inputs['user_id']=$user->id;
                $inputs['title']=$request->question;
                $inputs['description']=$request->answer;
                $inputs['product_id']=$request->product_id;
                $inputs['published']=$request->published ?? 0;
                Faq::create($inputs);

            return response()->json([
                'success' => true,'message' => 'Faq created successfull'
            ]);
    }

       /**
     * @OA\Get(
     *      path="/chowhub/faq/{product_id}",
     *      operationId="chowhubFaq",
     *      tags={"Faqs"},
     *
     *     summary="chowhubFaq",
     *     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *       @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="search title/description",
     *         required=false,
     *      ),

     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/FaqResponse")
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

    public function chouhubIndex($id,Request $request)
    {
        $search= $request['keyword']?? null;
        $faqs = ChowhubFaq::with('user','product')
        ->where(['product_id'=>$id,'published'=>1])
        ->where(function ($query) use ($search) {
                $query->where('title', "like", "%" . $search . "%");
                $query->orWhere('description', "like", "%" . $search . "%");})
        ->orderBy('id', 'DESC')->get();

        return  ChowhubFaqResource::collection($faqs);

    }


  /**
     * @OA\Post(
     *      path="/chowhub/faq/store",
     *      operationId="chowhubFaq store",
     *      tags={"Faqs"},
     *
     *     summary="chowhubFaq store",
     *  *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FaqRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/FaqResponse")
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
    public function chouhubStore(FaqRequest $request)
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


               $inputs['user_id']=$user->id;
               $inputs['title']=$request->question;
               $inputs['description']=$request->answer;

               $inputs['product_id']=$request->product_id;
               $inputs['published']=$request->published ?? 0;
               ChowhubFaq::create($inputs);


            return response()->json([
                'success' => true,'message' => 'Faq created successfull'
            ]);
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
