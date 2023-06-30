<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Storage;
use DataTables;
use App\Http\Requests\Admin\Page\PagesRequest;
use Illuminate\Support\Str;
use Auth;

class PageController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax())
        {
            $data = Post::with('users')->with('categories')->orderby('posts.id','DESC');

            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('status', function ($row)
                            {
                                if ($row->status == 1)
                                {
                                    $status = '<span class="label text-success d-flex">
                                                        <div class="dot-label bg-success me-1"></div>active
                                                    </span>';
                                } else
                                {
                                    $status = '<span class="label text-danger d-flex">
                                                        <div class="dot-label bg-danger me-1"></div> inactive
                                                    </span>';
                                }

                                return $status;
                            })
                            ->addColumn('action', function ($row)
                            {
                                $action = '<span class="action-buttons">
                                    <a  href="' . route("editPage", $row->id) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("deletePage", $row->id) . '"
                                            class="btn btn-sm btn-danger remove_us"
                                            title="Delete User"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-method="DELETE"
                                            data-confirm-title="Please Confirm"
                                            data-confirm-text="Are you sure that you want to delete this Category?"
                                            data-confirm-delete="Yes, delete it!">
                                            <i class="las la-trash"></i>
                                        </a>
                                ';
                                return $action;
                            })
                            ->rawColumns(['action', 'status'])
                            ->make(true)
            ;
        }


        return view("admin.pages.pageList");
    }

    //below function used for add page
    public function addPages()
    {
        $category = Category::where(['type' => 'Page', 'status' => 1])->get();
        return view("admin.pages.addpages", compact('category'));
    }

    //below function store data into  database
    public function store(PagesRequest $request)
    {
        if (!empty($request->feature_image))
        {
            $path = Storage::disk('s3')->put('images/pages', $request->feature_image);
            $path = Storage::disk('s3')->url($path);
        }

        // minified css code


        $request->css = str_replace(': ', ':', $request->css);
        $request->css = str_replace('<br />', '', $request->css);
        $request->css = str_replace(array(' {', ' }', '{ ', '; '), array('{', '}', '{', ';'), $request->css);
        $request->css = str_replace(array("\r\n", "\r", "\n", "\t", '{ '), '', $request->css);
        // setup the URL and read the CSS from a file
        $url = 'https://www.toptal.com/developers/cssminifier/raw';
        $css = $request->css;

        // init the request, set various options, and send it
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
            CURLOPT_POSTFIELDS => http_build_query(["input" => $css])
        ]);

        $minified = curl_exec($ch);

        // finally, close the request
        curl_close($ch);
        $user = Post::updateOrCreate([
                    'title' => $request->title,
                    'created_by' => Auth::User()->id,
                    'status' => $request->status,
                    'content' => $request->content,
                    'css' => $minified,
                    'slug' => Str::slug($request->title),
                    'category' => $request->category,
                    'feature_image' => $path ?? null
        ]);
        return redirect('admin/add-page')->with('success', 'Page Created Succesfully');
    }

    public function editPage($id)
    {
        $post = Post::find($id);
        $category = Category::where(['type' => 'Page', 'status' => 1])->get();
        return view('admin.pages.editPage', compact('post', 'category'));
    }

    //below function update the data
    public function updatePage(PagesRequest $request)
    {
        $post = Post::find($request->id);
        if (!empty($request->feature_image))
        {
            $path = Storage::disk('s3')->put('images/pages', $request->feature_image);
            $path = Storage::disk('s3')->url($path);
            $post->feature_image = $path;
        }



        // minified css code


        $request->css = str_replace(': ', ':', $request->css);
        $request->css = str_replace('<br />', '', $request->css);
        $request->css = str_replace(array(' {', ' }', '{ ', '; '), array('{', '}', '{', ';'), $request->css);
        $request->css = str_replace(array("\r\n", "\r", "\n", "\t", '{ '), '', $request->css);
        // setup the URL and read the CSS from a file
        $url = 'https://www.toptal.com/developers/cssminifier/raw';
        $css = $request->css;

        // init the request, set various options, and send it
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
            CURLOPT_POSTFIELDS => http_build_query(["input" => $css])
        ]);

        $minified = curl_exec($ch);

        // finally, close the request
        curl_close($ch);

        $post->title = $request->title;
        $post->status = $request->status;
        $post->content = $request->content;
        $post->css = $minified;

        $post->category = $request->category;

        $post->save();

        return redirect('admin/page')->with('success', 'Page Updated Successfully');
    }

    //  below function delete the data
    public function deletePage($id)
    {
        $post = Post::find($id)->delete();
        return redirect('admin/page')->with('success', 'Deleted Successfully');
    }

}
