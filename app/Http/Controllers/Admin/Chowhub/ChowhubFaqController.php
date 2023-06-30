<?php

namespace App\Http\Controllers\Admin\Chowhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChowhubFaq;

use App\Models\ChowhubProduct;

use DataTables;
use App\Http\Requests\Admin\Faq\AddFaq;
use App\Http\Requests\Admin\Faq\UpdateFaq;
class ChowhubFaqController extends Controller
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
            $data = ChowhubFaq::with('user')->select('chowhub_faqs.*')->orderby('chowhub_faqs.id','DESC');


            return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('published', function ($row)
            {
                if($row->published == 1){
                    $published =  '<span class="label text-success d-flex">
                                        <div class="dot-label bg-success me-1"></div>active
                                    </span>';
                }else{
                    $published =  '<span class="label text-danger d-flex">
                                        <div class="dot-label bg-danger me-1"></div> inactive
                                    </span>';
                }

                return $published;
            })
            ->addColumn('question', function ($row)
            {
                if(isset($row->title)){
                    $question =  strip_tags($row->title);
                }
                return $question;
            })


                    ->addColumn('action', function ($row)
                            {

                                $action = '<span class="action-buttons">
                                    <a  href="'.route("chowhub-questions.edit", $row).'" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="'.route("chowhub-questions.destroy", $row).'"
                                            class="btn btn-sm btn-danger remove_us"
                                            title="Delete User"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-method="DELETE"
                                            data-confirm-title="Please Confirm"
                                            data-confirm-text="Are you sure that you want to delete this Faqs?"
                                            data-confirm-delete="Yes, delete it!">
                                            <i class="las la-trash"></i>
                                        </a>
                                ';
                                return $action;
                            })

                            ->rawColumns(['action','published','question'])
                            ->make(true)
                            ;
        }

        return view('admin.chowhub.faqs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $products = ChowhubProduct::select('productName','id')->get();

        return view('admin.chowhub.faqs.addEdit',compact('products'));
    }

    /**
     * faqs a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddFaq $request)
    {


        $inputs = $request->all();
        $inputs['user_id']=auth()->user()->id;
        ChowhubFaq::create($inputs);
         return back()->with('success','Question addded successfully!');
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
        $faq=ChowhubFaq::find($id);
        $faqs = ChowhubFaq::where('id','!=',$id)->get();
        $products = ChowhubProduct::select('productName','id')->get();

        return view('admin.chowhub.faqs.addEdit',compact('faqs','faq','products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFaq $request, $id)
    {

        $inputs = $request->all();
        $inputs['user_id']=auth()->user()->id;
        ChowhubFaq::find($id)->update($inputs);

        return back()->with('success','Question updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ChowhubFaq::find($id)->delete();
        return back()->with('success','Question deleted successfully!');
    }



}
