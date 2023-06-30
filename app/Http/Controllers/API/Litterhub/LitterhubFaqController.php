<?php

namespace App\Http\Controllers\API\Litterhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Litterhub\LitterhubFaq;
use App\Models\User;
use App\Http\Resources\Faqs\LitterhubFaqResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\API\FaqRequest;
class LitterhubFaqController extends Controller
{
      /**
     * @OA\Get(
     *      path="litterhub/faq/{product_id}",
     *      operationId="litterhub faqs",
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
        if(isset($search)){
        $faqs = LitterhubFaq::with('user','product')
                ->where(['product_id'=>$id,'published'=>1])
                ->where(function ($query) use ($search) {
                        $query->where('title', "like", "%" . $search . "%");
                        $query->orWhere('description', "like", "%" . $search . "%");})
                ->orderBy('id', 'DESC')
                ->get();
            }else{

                $faqs = LitterhubFaq::where(['product_id'=>$id ,'published'=>1])
                ->orderBy('id', 'DESC')
                ->get();
            }

        return  LitterhubFaqResource::collection($faqs);

    }

  /**
     * @OA\Post(
     *      path="litterhub/faq/store",
     *      operationId="litterhub faqs store",
     *      tags={"Faqs"},
     *
     *     summary="Litterhub Faqs store",
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
                LitterhubFaq::create($inputs);

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
