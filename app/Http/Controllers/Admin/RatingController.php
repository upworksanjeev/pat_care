<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\RatingGallery;

use App\Models\Product;

use DataTables;

use App\Http\Requests\Admin\Rating\UpdateRating;
use Storage;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax())
        {
            $data = Rating::with('user','product')->orderby('ratings.id','DESC');

            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function ($row)
                            {
                                $action = '<span class="action-buttons">
                                    <a  href="' . route("ratings.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("ratings.destroy", $row) . '"
                                            class="btn btn-sm btn-danger remove_us"
                                            title="Delete User"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-method="DELETE"
                                            data-confirm-title="Please Confirm"
                                            data-confirm-text="Are you sure that you want to delete this Brand?"
                                            data-confirm-delete="Yes, delete it!">
                                            <i class="las la-trash"></i>
                                        </a>
                                ';
                                return $action;
                            })
                            ->addIndexColumn()
                            ->addColumn('product', function ($row)
                            {

                                if(!empty($row['product']->productName)){
                                    $product = $row['product']->productName;
                                }else{
                                    $product = null;
                                }

                                return $product;
                            })
                            ->rawColumns(['action','product'])
                            ->make(true)
            ;
        }

        return view('admin.rating.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


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
    public function edit(Rating $rating)
    {
        $ratingGallery=RatingGallery::where('rating_id',$rating->id)->get();
        $ratings = Rating::where('id', '!=', $rating->id)->get();
        $products = Product::select('id','productName')->get();

        return view('admin.rating.addEdit', compact('rating', 'ratings','products','ratingGallery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRating $request,$id)
    {
        $inputs = $request->all();


        $ratings=Rating::find($id);
        $rating = Rating::find($id)->update(
            [
                'product_id' => $request->product_id,
                'rating' => $request->rating,

                'title' => $request->title,
                'status' => $request->status,
                'description' => $request->description,

            ]);

        if (!empty($request->image))
            {
                foreach ($request->image as  $value) {

                    RatingGallery::create( [
                        'rating_id' => $ratings->id,
                        'image_path' => $value
                    ]);
                }

            }

        return back()->with('success', 'Rating updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RatingGallery::where('rating_id', $id)->delete();
        Rating::find($id)->delete();
        return back()->with('success', 'Rating deleted successfully!');
    }
    public function save_photo(Request $request)
    {
        if ($request->file('images'))
        {
            $path = Storage::disk('s3')->put('images/rating', $request->images);
            $path = Storage::disk('s3')->url($path);
            $id = substr($path, -8, 1);
            return Response()->json([
                        "success" => true,
                        "image" => $path,
                        "id" => $id
            ]);
        }

        return Response()->json([
                    "success" => false,
                    "image" => ''
        ]);
    }

}
